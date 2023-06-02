@if(count($data))
    <div class='table-responsive'>
        <table class='{!! $class ?? "table table-striped table-hover table-list-default" !!}'>
            <thead>
            <tr>
                @foreach ($table as $k => $rs)
                    @php $class = !empty($rs['class']) ? (is_string($rs['class'] ? $rs['class'] : null)) : ""; @endphp
                    @if(substr($k, 0, 1) != "_")
                        <th class="{!! $class !!}">{!! __($k) !!}</th>
                    @endif
                @endforeach
                @if(!empty($actions))
                    <th colspan="{!! count($actions) !!}"></th>
                @endif
            </tr>
            </thead>

            <tbody>
            @foreach ($data as $k => $rs)
                <tr class="{!! !empty($class_line) ? $class_line($rs) : "" !!}">
                    @foreach ($table as $j => $column)
                        @php $field = !empty($column['field']) ? $column['field'] : null; @endphp
                        @php $class = !empty($column['class']) ? (is_string($column['class']) ? $column['class'] : $column['class']($rs)) : ""; @endphp
                        <td class="{!! $class !!}">{!! empty($column['action']) ? ($rs[$field] ?: $rs->$field) : $column['action']($rs) !!}</td>
                    @endforeach

                    @if(!empty($actions))
                        @foreach ($actions as $k => $action)
                            <td class='action-{!! $k !!} {{ isset($action['class']) ? $action['class'] : "" }}'>
                                @if (!empty($action['action']))
                                    @php
                                        $id = $action['action']($rs);
                                        switch($k){
                                            case 'show':
                                            echo "<a href='{$action['action']($rs)}' class='btn btn-secondary btn-sm btn-show'><i class=\"fas fa-search-plus\"></i></a>";
                                            break;
                                            case 'edit':
                                            echo "<a href='{$action['action']($rs)}' class='btn btn-primary btn-sm btn-edit-by-link'><i class='fa fa-edit'></i></a>";
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
                                            $html .= "<a href='javascript:void(1)' data-id='" . $form_id . "' class='btn btn-danger btn-sm btn-frm-remove'><i class='fa fa-trash'></i></a>";
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
    </div>
    @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator && $data->toArray()['total'] > $data->toArray()['per_page'])
        <div class='card-footer'>
            <div class='pagination'>
                {!! $data->appends(request()->except(config('bhcosta90-package.pagination_except') + ['_token', 'q']))->links() !!}
            </div>
        </div>
    @endif
@endif
