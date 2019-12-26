<div class="row footer-statics">
    {!! DbView::make(core()->getCurrentChannel())->field('footer_content')->render() !!}
</div>