<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.about.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.catering.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.faq.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.press.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.privacy_policy.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.terms_conditions.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.refund_cancellation.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.catering.enquiry.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.corporate_orders.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.careers.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.contact.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.business.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.catering.query.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.feedback.get') }}</loc>
    </url>
    <url>
        <loc>{{ route('items.search.xprs-menu.get')  }}</loc>
    </url>
    <url>
        <loc>{{ route('items.hot_deals.get')  }}</loc>
    </url>
    @foreach($items as $item)
        <url>
            <loc>{{ route('items.xprs-menu.single.get', ['slug' => $item->itemable->slug ]) }}</loc>
        </url>
    @endforeach
</urlset>