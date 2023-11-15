@component('mail::message')
    # メールアドレスの確認

    以下のボタンをクリックして、メールアドレスを確認してください。

    @component('mail::button', ['url' => $actionUrl])
        メールアドレスを確認する
    @endcomponent

    このメールのリンクは、60分間有効です。

    もしメールアドレスの確認を依頼しなかった場合は、追加のアクションは不要です。

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
