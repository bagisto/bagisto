@component('shop::emails.layout')
    <!-- Title -->
    <div style="margin-bottom: 34px;">
        <span style="font-size: 22px; font-weight: 600; color: #121A26;">
            @lang('shop::app.rma.mail.seller-conversation.title')
        </span><br>

        <!-- Heading -->
        <p style="font-size: 16px; color: #5E5E5E; line-height: 24px;">
            @lang('shop::app.rma.mail.seller-conversation.heading', ['name' => $conversation['customerName']]),👋
        </p>

        <!-- conversation -->
        <p style="font-size: 16px; color: #6B7280; line-height: 24px;">
            @lang('shop::app.rma.mail.seller-conversation.quotes')
        </p>

        <div style="margin-bottom: 20px; margin-top: 20px; display: flex; flex-direction: row; justify-content: space-between;">
            <div style="line-height: 25px;">
                <!-- message -->
                <div style="font-size: 16px; font-weight: bold; color: #1F2937;">
                    @lang('shop::app.rma.mail.seller-conversation.message')
                </div>

                <div>
                    {{ $conversation['message'] }}
                </div>
            </div>
        </div><br><br><br>
    </div>
@endcomponent
