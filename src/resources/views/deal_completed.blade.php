@component('mail::message')
# 取引完了のお知らせ

{{ $buyer->name }} さんが、以下の商品を取引完了にしました。

**商品名:** {{ $product->name }}

---

これで取引が完了しました。ありがとうございました！

@endcomponent