<div class="panel panel-default">
    <div class="panel-body">
        <?php if($filter!=null) { ?>
            <?php  Easol_Widget::show("DataFilterWidget", ['filter'=>$filter]) ?>
        <?php }    ?>
        <div class="clearfix"></div>

        <?php if (count($columns) > 0): ?>
   
            <div class="dataTableGrid">
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <?php
                                foreach($columns as $column){
                                    $dbColName='';
                                    if(is_array($column)){
                                        $colName = $column['title'];
                                        $dbColName = $column['name'];
                                    }
                                    else {
                                        $colName = $column;
                                        $dbColName = $column;
                                    }

                                    ?>
                                    <?php if(is_array($column) && isset($column['sortable']) && $column['sortable']==true){ ?>
                                       <?php
                                        $sortIcon = 'glyphicon-sort';
                                        $getVars = $_GET;

                                        if(!isset($getVars['filter'])){
                                            $getVars['filter']=[];
                                        }

                                        $getVars['filter']['Sort'] = [];
                                        $getVars['filter']['Sort']['column'] = $column['sortField'];
                                        $getVars['filter']['Sort']['type'] = 'ASC';
                                        if(isset($_GET['filter']) && isset($_GET['filter']['Sort']['type']) && $_GET['filter']['Sort']['column'] == $column['sortField']){
                                            if($_GET['filter']['Sort']['type']=='ASC') {
                                                $sortIcon = 'glyphicon-sort-by-attributes';
                                                $getVars['filter']['Sort']['type'] = 'DESC';
                                            }
                                            else {
                                                $sortIcon = 'glyphicon-sort-by-attributes-alt';
                                            }

                                        }


                                        ?>
                                        <th ><a href="<?= (explode("/?",$_SERVER['REQUEST_URI'])[0]) ?>/?<?= http_build_query($getVars); ?>"><?= $colName ?> <span class="glyphicon <?= $sortIcon ?>"> </span></a></th>

                                    <?php } else { ?>
                                        <th><?= $colName ?></th>
                                    <?php } ?>
                                    <?php
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($query->result() as $row): ?>
                            <tr>                    
                                <?php foreach($columns as $k=>$column): ?>
                                    <?php 
                                        $colType='text';
                                        if(is_array($column)){
                                            $colName = $column['name'];
                                            if(array_key_exists('type',$column))
                                                $colType=$column['type'];
                                        }
                                        else $colName = $column;
                                    ?>

                                    <td>
                                        <?php if(isset($row->$colName)): ?>
                                            
                                            <?php
                                                $value=$row->$colName;
                                                if(isset($column['value'])){
                                                    $value=$column['value']($row);
                                                }
                                            ?>

                                            <?php if($colType=='url'): ?>
                                                <a href="<?= $column['url']($row) ?>"><?= $value ?></a>
                                            <?php elseif ($link = report_column_link($k + 1, $row, $links)): ?>
                                                <a href="<?php echo $link ?>"><?php echo $value ?></a>
                                            <?php else: ?>
                                                <?php echo $value; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>



                                    </td>

                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


        <?php endif; ?>

        <?php if($pagination!=null){ ?>
            <?php Easol_Widget::show("PaginationWidget",$pagination) ?>
        <?php } ?>


            <div class="row pull-right">
                <?php if(isset($filter) && isset($filter['fields']) && isset($filter['fields']['Result'])) { ?>
                    <div class="col-md-5">
                        <select class="form-control" id="filter-result" >
                            <?php
                            foreach($filter['fields']['Result']['range']['set'] as $key => $value){
                                ?>
                                <option value="<?= $key ?>" <?php if($key==$filter['fields']['Result']['default']) echo 'selected' ?>><?= $value ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                <?php }  ?>
                <?php if($downloadCSV==true){
                $protocol   = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false) ? 'http' : 'https';
                $host       = $_SERVER['HTTP_HOST'];
                $script     = $_SERVER['SCRIPT_NAME'];
                $script     = explode("/", $script);
                $script     = "/".$script[1]."/";
                $all_pages = ['dashboard','student','cohorts'];
                foreach ($all_pages as $page){
                    $page_url = strpos($_SERVER ['REQUEST_URI'], $page);
                    if ($page_url !== false) {
                        $params = substr($_SERVER ['REQUEST_URI'], $page_url);
                        $page_url2 = strpos($params, 'index');
                        if ($page_url2 !== false) {
                            $params = str_replace('index','csv', $params);
                            $params = ($_SERVER['QUERY_STRING']) ? $params.'?'.$_SERVER['QUERY_STRING']."&downloadcsv=y" : $params."?downloadcsv=y";
                        }else {
                            $params = ($_SERVER['QUERY_STRING']) ? $params.'/csv/?'.$_SERVER['QUERY_STRING']."&downloadcsv=y" : $params."/csv/?downloadcsv=y";
                        }
                        break;
                    }else{
                        $params = ($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING']."&downloadcsv=y" : "?downloadcsv=y";
                    }
 
                }
                $url    = $protocol . '://' . $host . $script . $params;
                //$url = ($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING']."&downloadcsv=y" : "?downloadcsv=y";
                ?>
            <div class="col-md-7">    
                <a href="<?=$url;?>"><button class="btn btn-default">Download CSV <i class="fa fa-download"> </i> </button></a>
            </div>
                <?php } ?>

            </div>
            <div class="clearfix"></div>
    </div>
</div>


