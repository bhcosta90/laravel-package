<?php


namespace BRCas\Laravel\Traits\Query;


trait IndexTrait
{
    /**
     * @return mixed
     */
    public abstract function model();

    /**
     * @param $result
     * @param array $dataSend
     * @return mixed
     */
    public function query($result, array $dataSend)
    {
        foreach ($dataSend as $k => $data) {
            $dados = explode('_', $k);
            if (count($dados) > 1) {
                $type = array_shift($dados);
                $tabela = str_replace('|', '.', array_shift($dados));
                if ($data) {
                    switch ($type) {
                        case 'like':
                            $result = $result->where("$tabela", "like", "$data%");
                            break;

                        case 'equal':
                            $result = $result->where("$tabela", "=", "$data");
                            break;
                    }
                }
            }
        }

        if (array_key_exists("sql", $dataSend)) {
            print $result->toRawSql() and exit;
        }

        $data = !$this->getTotalPaginate()
            ? $result
            : $result->paginate(
                $this->getTotalPaginate(),
                ["*"],
                'page',
                (int)(request()->input('pageIndex') ?? request()->input('page', 0))
            );

        return $data;
    }

    protected function getTotalPaginate()
    {
        return request()->input('pageSize', env('TOTAL_PAGINATE', 30));
    }
}
