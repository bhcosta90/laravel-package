@if(count($results))
    <table class='{!! $class ?? "table table-striped table-hover table-list-default" !!}'>
        <thead>
        <tr>
            @foreach ($table as $k => $rs)
                @php $class = !empty($rs['class']) ? $rs['class'] : ""; @endphp
                <th class="{!! $class !!}">{!! __($k) !!}</th>
            @endforeach
            @if(!empty($actions))
                <th colspan="{!! count($actions) !!}"></th>
            @endif
        </tr>
        </thead>

        <tbody>
        @foreach ($results as $k => $rs)
            <tr class="{!! !empty($class_line) ? $class_line($rs) : "" !!}">
                @foreach ($table as $j => $column)
                    @php $field = !empty($column['field']) ? $column['field'] : null; @endphp
                    @php $class = !empty($column['class']) ? $column['class'] : ""; @endphp
                    <td class="{!! $class !!}">{!! empty($column['action']) ? $rs->$field : $column['action']($rs) !!}</td>
                @endforeach

                @if(!empty($actions))
                    @foreach ($actions as $k => $action)
                        <td class='action-{!! $k !!} {{ isset($action['class']) ? $action['class'] : "" }}'>
                            @if (!empty($action['action']))
                                @php
                                    $id = $action['action']($rs);
                                    switch($k){
                                        case 'show':
                                        echo "<a href='{$action['action']($rs)}' class='btn-secondary btn-sm btn-show'><i class=\"fas fa-search-plus\"></i></a>";
                                        break;
                                        case 'edit':
                                        echo "<a href='{$action['action']($rs)}' class='btn-primary btn-sm btn-edit'><i class='fa fa-edit'></i></a>";
                                        break;
                                        case 'delete':
                                        $form_id = uniqid();
                                        $html = Form::open([
                                            'url' => $action['action']($rs),
                                            'id' => "frm-" . $form_id,
                                            'method' => 'DELETE',
                                            'style' => 'display:none;',
                                            'class' => 'form-delete-confirmation'
                                        ]);
                                        $html .= "<button>{$id}</button>";
                                        $html .= Form::close();
                                        $html .= "<a href='javascript:void(1)' onclick='$(this).parent().find(\"#frm-{$form_id}\").submit()' class='btn-danger btn-sm btn-frm-remove'><i class='fa fa-trash'></i></a>";
                                        print $html;
                                        break;
                                        default:
                                            echo $action['action']($rs);
                                    }
                                @endphp
                            @endif
                        </td>
                    @endforeach
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>

    @if($results instanceof \Illuminate\Pagination\LengthAwarePaginator && $results->toArray()['total'] > $results->toArray()['per_page'])
        <div class='card-footer'>
            <div class='pagination'>
                {!! $data->appends(request()->except([
                    'account_id', 'account_name', 'q'
                ]))->links() !!}
            </div>
        </div>
    @endif
@endif
