$(function () {
	var SectionsTable = $('table#managesections').dataTable().api();

	$("[name='term']").on('change', function() {
		SectionsTable.column(0).search($(this).val(), true, false).draw();
	});

	$("[name='year']").on('change', function() {
		SectionsTable.column(1).search($(this).val(), true, false).draw();
	});

	$("[name='course']").on('change', function() {
		SectionsTable.column(2).search($(this).val(), true, false).draw();
	});

	$("[name='educator']").on('change', function() {
		SectionsTable.column(5).search($(this).val(), true, false).draw();
	});
        
	$("[name='filter[PageLength]']").on('change', function() {
		SectionsTable.page.len($(this).val()).draw();
	});

	
	/* Apply the default filters as set by the server */
	$('#dataGridFormFilter select').trigger('change');
})