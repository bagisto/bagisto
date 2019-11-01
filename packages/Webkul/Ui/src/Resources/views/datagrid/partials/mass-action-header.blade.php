<thead v-if="massActionsToggle">
    @if (isset($results['massactions']))
        <tr class="mass-action" v-if="massActionsToggle" style="height: 65px;">
            <th colspan="100%">
                <div class="mass-action-wrapper" style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;">

                    <span class="massaction-remove" v-on:click="removeMassActions" style="margin-right: 10px; margin-top: 3px;">
                        <span class="icon checkbox-dash-icon"></span>
                    </span>

                    <form method="POST" id="mass-action-form" style="display: inline-flex;" action="" onsubmit="return confirm('{{ __('ui::app.datagrid.click_on_action') }}')">
                        @csrf()

                        <input type="hidden" id="indexes" name="indexes" v-model="dataIds">

                        <div class="control-group">
                            <select class="control" v-model="massActionType" @change="changeMassActionTarget" name="massaction-type" required>
                                <option v-for="(massAction, index) in massActions" :key="index" :value="massAction.type">@{{ massAction.label }}</option>
                            </select>
                        </div>

                        <div class="control-group" style="margin-left: 10px;" v-if="massActionType == 'update'">
                            <select class="control" v-model="massActionUpdateValue" name="update-options" required>
                                <option v-for="(massActionValue, id) in massActionValues" :value="massActionValue">@{{ id }}</option>
                            </select>
                        </div>

                        <input type="submit" class="btn btn-sm btn-primary" style="margin-left: 10px;">
                    </form>
                </div>
            </th>
        </tr>
    @endif
</thead>