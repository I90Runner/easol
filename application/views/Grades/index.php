<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Grades</h1>
        <br/><br/>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php Easol_Widget::show("DataTableWidget",
            [
                'query' => $query,
                'pagination' => $pagination,
                'filter'    =>  $filter,
                'colOrderBy'    =>  $colOrderBy,
                'colGroupBy'    =>  $colGroupBy,
                'columns'   =>
                    [
                        ['name' => 'CourseTitle', 'title' => 'Course Name'],
                        ['name' => 'LocalCourseCode', 'title' => 'Section Name'],
                        ['name' => 'ClassPeriodName', 'title' => 'Period'],
                        ['name' => 'Term', 'title' => 'Term'],
                        ['name' => 'FirstName', 'title' => 'Educator','value' => function($model){ return $model->FirstName.' '.$model->LastSurname;  }],
                        ['name' => 'StudentCount', 'title' => 'Students' ]
                    ]
            ]

        ) ?>
    </div>
</div>