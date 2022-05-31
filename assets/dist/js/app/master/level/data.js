var save_label;
var table;

$(document).ready(function() {
  ajaxcsrf();

  table = $("#level").DataTable({
    initComplete: function() {
      var api = this.api();
      $("#level_filter input")
        .off(".DT")
        .on("keyup.DT", function(e) {
          api.search(this.value).draw();
        });
    },
    dom:
      "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [
      {
        extend: "copy",
        exportOptions: { columns: [1, 2] }
      },
      {
        extend: "print",
        exportOptions: { columns: [1, 2] }
      },
      {
        extend: "excel",
        exportOptions: { columns: [1, 2] }
      },
      {
        extend: "pdf",
        exportOptions: { columns: [1, 2] }
      }
    ],
    oLanguage: {
      sProcessing: "loading..."
    },
    processing: true,
    serverSide: true,
    ajax: {
      url: base_url + "level/data",
      type: "POST"
      //data: csrf
    },
    columns: [
      {
        data: "level_id",
        orderable: false,
        searchable: false
      },
      { data: "level_name" },
      { data: "dep_name" },
      {
        data: "bulk_select",
        orderable: false,
        searchable: false
      }
    ],
    order: [[1, "asc"]],
    rowId: function(a) {
      return a;
    },
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      var index = page * length + (iDisplayIndex + 1);
      $("td:eq(0)", row).html(index);
    }
  });

  table
    .buttons()
    .container()
    .appendTo("#level_wrapper .col-md-6:eq(0)");

  $("#myModal").on("shown.modal.bs", function() {
    $(':input[name="banyak"]').select();
  });

  $("#select_all").on("click", function() {
    if (this.checked) {
      $(".check").each(function() {
        this.checked = true;
      });
    } else {
      $(".check").each(function() {
        this.checked = false;
      });
    }
  });

  $("#level tbody").on("click", "tr .check", function() {
    var check = $("#level tbody tr .check").length;
    var checked = $("#level tbody tr .check:checked").length;
    if (check === checked) {
      $("#select_all").prop("checked", true);
    } else {
      $("#select_all").prop("checked", false);
    }
  });

  $("#bulk").on("submit", function(e) {
    if ($(this).attr("action") == base_url + "level/delete") {
      e.preventDefault();
      e.stopImmediatePropagation();

      $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        type: "POST",
        success: function(respon) {
          if (respon.status) {
            Swal({
              title: "Successful",
              text: respon.total + " data deleted successfully",
              type: "success"
            });
          } else {
            Swal({
              title: "Failed",
              text: "No data selected",
              type: "error"
            });
          }
          reload_ajax();
        },
        error: function() {
          Swal({
            title: "Failed",
            text: "There is data in use",
            type: "error"
          });
        }
      });
    }
  });
});

function load_department() {
  var department = $('select[name="dep_name"]');
  department.children("option:not(:first)").remove();

  ajaxcsrf(); // get csrf token
  $.ajax({
    url: base_url + "department/load_department",
    type: "GET",
    success: function(data) {
      //console.log(data);
      if (data.length) {
        var datadepartment;
        $.each(data, function(key, val) {
          datadepartment = `<option value="${val.dep_id}">${val.dep_name}</option>`;
          department.append(datadepartment);
        });
      }
    }
  });
}

function bulk_delete() {
  if ($("#level tbody tr .check:checked").length == 0) {
    Swal({
      title: "Failed",
      text: "No data selected",
      type: "error"
    });
  } else {
    $("#bulk").attr("action", base_url + "level/delete");
    Swal({
      title: "You sure?",
      text: "Data will be deleted!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Delete!"
    }).then(result => {
      if (result.value) {
        $("#bulk").submit();
      }
    });
  }
}

function bulk_edit() {
  if ($("#level tbody tr .check:checked").length == 0) {
    Swal({
      title: "Failed",
      text: "No data selected",
      type: "error"
    });
  } else {
    $("#bulk").attr("action", base_url + "level/edit");
    $("#bulk").submit();
  }
}
