Vue.component('verse', {

    data: function () {
        return {}
    },
    props: ["id", 'text'],
    template: `
<div class="row">
    <div class="col">
        <div class="verse">
            <span class="verse-number">{{id}}.</span>
            <span class="verse-text">{{text}}</span>
        </div>
    </div>
</div>
    `

});