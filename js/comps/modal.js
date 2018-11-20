Vue.component("modal",{

    data:()=>{
        "use strict";
        return {}
    },
    props:['title','body', "footer", 'id', 'button',"target"],
    template:`
    <div class="modal-wrapper">

        <div class="modal" :id="id" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{title}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{body}}</p>
                    </div>
                    <div class="modal-footer">
                        {{footer}}
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
<button type="button" class="btn btn-primary" data-toggle="modal" :data-target="'#'+id">
  {{button}}
</button>
</div>
    `
});