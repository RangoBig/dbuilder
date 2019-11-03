@section('headStyle')
    <style>
        .sort.sort-active {
            color: #000;
            font-weight: bold;
        }
    </style>
@append
<?php
$list_options = $config['list_options'];
$loadSBox = false;
$loadDatePicker = false;
?>
@if(isset($errors))
<div class="panel">
    <div class="panel-body">
        <span class="red">emsg:{{$errors}}</span>
    </div>
</div>
@endif

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?=isset($navMap[$snakeName]['text']) ? $navMap[$snakeName]['text'] : strtoupper($snakeName)?>
            列表</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group btn-group-sm" role="group">
                    @if($list_options['create'])
                        <a href="{{URL::to('admin/'.$reducName.'/edit')}}" class="btn btn-primary">新建</a>
                    @endif
                    <a class="btn btn-danger delete-selected">删除</a>
                    <a class="btn btn-default">导出</a>
                </div>
            </div>
        </div>
        <br>
        <form class="list-form" action="" method="get">
            <input type="hidden" name="list_sort_asc" value="{{Input::get('list_sort_asc') !== null ? Input::get('list_sort_asc') : 1}}">
            <input type="hidden" name="list_order_by" value="">
            <table class="table table-bordered responsive table-hover table-striped">
                <thead>
                <tr>
                    <th>
                        <div class="checkbox checkbox-replace">
                            <input type="checkbox" class="item-all">
                        </div>
                    </th>
                    <?php foreach($config['fields'] as $field=>$settings):?>
                    <?php if($settings['list']['show']):?>
                    <th @if($settings['list']['sort'])
                        class="sort @if($field === Input::get('list_order_by')) sort-active @endif"
                        data-field="{{$field}}" @endif
                    ><?=is_array($settings) && isset($settings['label']) ? $settings['label'] : strtoupper($field)?>
                        <span class="pull-right">
                            @if($field === Input::get('list_order_by'))  @if(Input::get('list_sort_asc') == 1) <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>  @endif @endif
                        </span>
                    </th>
                    <?php endif;?>
                    <?php endforeach;?>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    @foreach($config['fields'] as $field=>$fieldConfig)
                        @if($fieldConfig['list']['show'])
                            @if(isset($fieldConfig['list']['search']) && $fieldConfig['list']['search'] !== false)
                                <td>
                                    @if($fieldConfig['form']['type'] == 'select' || ($fieldConfig['form']['type'] === 'radio' || $fieldConfig['form']['type'] == 'checkbox'))
                                        <?php $loadSBox = true;?>
                                        @if(isset($fieldConfig['form']['options']) && $fieldConfig['form']['options'])
                                            <select name="{{$field}}" id="{{$field}}" class="selectboxit">
                                                <option value="" class="default-value">请选择</option>
                                                @foreach($fieldConfig['form']['options'] as $option => $text)
                                                    <option value="{{$option}}" @if(Input::get($field) && Input::get($field) === $option) selected @endif>{{$text}}</option>
                                                @endforeach
                                            </select>
                                        @elseif(isset($fieldConfig['relation']['type']) && $fieldConfig['relation']['type'] )
                                            {{View::make('components.relation_select',array(
                                            'fieldConfig'=>$fieldConfig,'field' =>  $field, ))}}
                                        @endif
                                    @elseif($fieldConfig['form']['type'] === 'date')
                                        <?php $loadDatePicker = true;?>
                                        <input type="text" name="{{$field}}" id="{{$field}}" class="form-control datepicker" data-format="yyyy-MM-dd" value="{{Input::get($field)}}">
                                    @else
                                        <input type="text" name="{{$field}}" id="{{$field}}"
                                               value="{{Input::get($field)}}" class="form-control input-sm">
                                    @endif
                                </td>
                            @else
                                <td></td>
                            @endif
                        @endif
                    @endforeach
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="submit" class="btn btn-primary">搜索</button>
                            <button type="reset" onclick="resetForm(this)" class="btn btn-warning hidden">重置</button>
                        </div>
                    </td>
                </tr>
                <?php foreach($models as  $model):?>
                <tr>
                    <td width="18px">
                        <div class="checkbox checkbox-replace">
                            <input type="checkbox" name="d_delete_select" class="item" value="{{$model->id}}">
                        </div>
                    </td>
                    <?php foreach($config['fields'] as $filed=>$settings):?>
                    <?php if($settings['list']['show']):?>
                    <?php
                    $value = $model->$filed;
                    /* 字段在列表中需要翻译 */
                    if (array_key_exists($filed, $config['relations'])) {
                        $value = $model->$config['relations'][$filed]['as'];
                    }
                    ?>
                    <td>{{$value}}</td>
                    <?php endif;?>
                    <?php endforeach;?>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            @if($list_options['update'])
                                <a href="{{URL::to('admin/'.$reducName.'/edit/'.$model->id)}}"
                                   class="btn btn-primary">编辑</a>
                            @endif
                            @if($list_options['delete'])
                                <a href="{{URL::to('admin/'.$reducName.'/delete/'.$model->id)}}"
                                   class="btn btn-danger">删除</a>
                            @endif
                            @if(View::exists('admin.'.$snakeName.'.list_item_links'))
                                @include('admin.'.$snakeName.'.list_item_links',array('model'=>$model))
                            @endif
                        </div>
                    </td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </form>
        <div class="pull-right">
            {{$models->appends(Input::all())->links()}}
        </div>
    </div>
</div>

@section('styles')
    {{HTML::style('assets/js/datatables/responsive/css/datatables.responsive.css')}}

    @if($loadSBox)
        {{HTML::style('assets/js/selectboxit/jquery.selectBoxIt.css')}}
    @endif
@append

@section('scripts')
    {{HTML::script('assets/js/jquery.dataTables.min.js')}}
    {{HTML::script('assets/js/datatables/jquery.dataTables.columnFilter.js')}}
    @if($loadSBox)
        {{HTML::script('assets/js/selectboxit/jquery.selectBoxIt.min.js')}}
    @endif
    @if($loadDatePicker)
        {{HTML::script('assets/js/bootstrap-datepicker.js')}}
    @endif
@append

@section('footScript')
    <script>
        $(document).ready(function(){
            $('th.sort').click(function(){
                var $th = $(this);
                $('input[name="list_order_by"]').val($th.data('field'));
                $('input[name="list_sort_asc"]').val($th.find('i').hasClass('fa-sort-asc') ? 0 : 1);
                $('form.list-form').submit();
            });

            $('input.item-all').change(function(){
                var $this = $(this),
                        $items = $('input.item');
                if($this.is(':checked')){
                    $items.prop('checked','checked');
                }else{
                    $items.removeProp('checked');
                }
                $items.trigger('change');
            });


            $('a.delete-selected').click(function(){
                var ids = [],
                        $items = $('input.item:checked');
                $items.each(function(i){
                    ids.push($(this).val());
                });
                var idsStr = ids.join(',');
                confirmModal({
                    message  :   '确认删除：'+idsStr,
                    onOk:   function(){
                        $.post('{{URL::to('admin/'.$reducName.'/delete')}}',{"ids":idsStr},function(resp){
                            if(resp.success){
                                window.location.href = resp.data.redirect_url;
                            }
                        },'json');
                    }
                });
                return false;
            });
        });
    </script>
@append

@section('modals')
    <div class="modal fade" id="confirm-modal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">操作确认</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default cancel" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-info ok">确认</button>
                </div>
            </div>
        </div>
    </div>
@stop