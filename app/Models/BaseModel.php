<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use DB;


abstract Class BaseModel extends Model{

    public static function debugSql($log = null){

        if(getenv('APP_DEBUG') === "true"){

            DB::listen(function ($sql) use ($log) {

                foreach ($sql->bindings as $i => $binding) {
                    if ($binding instanceof \DateTime) {
                        $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                    } else {
                        if (is_string($binding)) {
                            $sql->bindings[$i] = "'$binding'";
                        }
                    }
                }

                $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);
                $query = vsprintf($query, $sql->bindings);

                if($log) {
                  return Log::info($query);
                }

                $query = str_replace("select ","<b>SELECT</b> ",$query);
                $query = str_replace(" from "," \n<b>FROM</b> ",$query);
                $query = str_replace(" where "," \n<b>WHERE</b> ",$query);
                $query = str_replace(" left join "," \n<b>LEFT JOIN</b> ",$query);
                $query = str_replace(" inner join "," \n<b>INNER JOIN</b> ",$query);
                $query = str_replace(" group by "," \n<b>GROUP BY</b>",$query);
                $query = str_replace(" having "," \n<b>HAVING</b>",$query);
                $query = str_replace(" order by "," \n<b>ORDER BY</b>",$query);
                die("<pre>".$query."</pre>");
            });
        }
    }


    public static function beginTransaction(){
        DB::beginTransaction();
    }

    public static function commit(){
        DB::commit();
    }

    public static function rollBack(){
        DB::rollBack();

    }
}