@component('admin::emails.layout')
    <!-- Title -->
    <div style="margin-bottom: 34px;">
        <span style="font-size: 22px; font-weight: 600; color: #121A26;">
            @lang('admin::app.emails.rma.conversation.title')
        </span><br>

        <!-- Heading -->
        <p style="font-size: 16px; color: #5E5E5E; line-height: 24px;">
            @lang('admin::app.emails.rma.conversation.heading', ['name' => core()->getAdminEmailDetails()['name']]),👋
        </p>

        <!-- Conversation -->
        <p style="font-size: 16px; color: #6B7280; line-height: 24px;">
            @lang('admin::app.emails.rma.conversation.quotes')
        </p>

        <div style="margin-bottom: 20px; margin-top: 20px; display: flex; flex-direction: row; justify-content: space-between;">
            <div style="line-height: 25px;">
                <!-- Message -->
                <div style="font-size: 16px; font-weight: bold; color: #1F2937;">
                    @lang('admin::app.emails.rma.conversation.message')
                </div>

                <div>
                    {{ $rmaMessage->message }}
                </div>
            </div>
        </div>
    </div>
@endcomponent