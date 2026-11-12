<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\User\Models\AdminProxy;

return new class extends Migration
{
    /**
     * Turn the order-only notifications into typed ones for an audience, read per reader.
     */
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('audience')->nullable()->after('type');
            $table->json('data')->nullable()->after('audience');
            $table->nullableMorphs('notifiable');

            $table->index(['audience', 'type']);
        });

        Schema::create('notification_reads', function (Blueprint $table) {
            $table->integer('notification_id')->unsigned();
            $table->morphs('reader');
            $table->timestamp('read_at')->nullable();

            $table->primary(['notification_id', 'reader_type', 'reader_id']);

            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
        });

        $this->convertOrderNotifications();

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'read']);

            $table->string('audience')->nullable(false)->change();
        });
    }

    /**
     * Put the order columns back, keeping only the admin notifications raised for a placed order that still exists.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->boolean('read')->default(0)->after('type');
            $table->integer('order_id')->unsigned()->nullable()->after('read');
        });

        DB::table('notifications')
            ->where(fn ($query) => $query->where('type', '!=', 'order.placed')->orWhere('audience', '!=', 'admin'))
            ->delete();

        DB::table('notifications')->orderBy('id')->chunkById(500, function ($notifications) {
            foreach ($notifications as $notification) {
                $data = json_decode($notification->data ?? '[]', true) ?: [];

                DB::table('notifications')->where('id', $notification->id)->update([
                    'type' => 'order',
                    'order_id' => $data['order_id'] ?? null,
                    'read' => DB::table('notification_reads')->where('notification_id', $notification->id)->exists(),
                ]);
            }
        });

        DB::table('notifications')
            ->whereNotExists(fn ($query) => $query->select(DB::raw(1))->from('orders')->whereColumn('orders.id', 'notifications.order_id'))
            ->delete();

        Schema::dropIfExists('notification_reads');

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['audience', 'type']);
            $table->dropMorphs('notifiable');
            $table->dropColumn(['audience', 'data']);

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Rewrite each order notification as a placed order for every admin, keeping one already read as read for each.
     */
    protected function convertOrderNotifications(): void
    {
        $adminType = (new (AdminProxy::modelClass()))->getMorphClass();

        $adminIds = DB::table('admins')->pluck('id');

        DB::table('notifications')->orderBy('id')->chunkById(500, function ($notifications) use ($adminType, $adminIds) {
            $orders = DB::table('orders')
                ->whereIn('id', $notifications->pluck('order_id'))
                ->get(['id', 'increment_id', 'customer_first_name', 'customer_last_name', 'base_grand_total', 'base_currency_code'])
                ->keyBy('id');

            foreach ($notifications as $notification) {
                $order = $orders->get($notification->order_id);

                DB::table('notifications')->where('id', $notification->id)->update([
                    'type' => 'order.placed',
                    'audience' => 'admin',
                    'data' => json_encode([
                        'order_id' => (int) $notification->order_id,
                        'increment_id' => $order?->increment_id,
                        'customer_name' => trim(($order?->customer_first_name ?? '').' '.($order?->customer_last_name ?? '')),
                        'base_grand_total' => (float) ($order?->base_grand_total ?? 0),
                        'base_currency_code' => $order?->base_currency_code,
                    ]),
                ]);

                if (! $notification->read) {
                    continue;
                }

                DB::table('notification_reads')->insert($adminIds->map(fn ($adminId) => [
                    'notification_id' => $notification->id,
                    'reader_type' => $adminType,
                    'reader_id' => $adminId,
                    'read_at' => $notification->updated_at,
                ])->all());
            }
        });
    }
};
