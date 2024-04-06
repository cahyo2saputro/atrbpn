
//var base_url = window.location.origin;
//var getUrl = window.location;
//var base_url =  getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[0];
var base_url = "http://localhost/warhal.com/";
$(document).ready(function(){
   

    $('#addInvoice').click(function () {
        var uraian      = $("#description").val();
        var nilai       = $("#nilai").val();

        $.ajax({
          type: 'POST',
          url: base_url+"/ajax/addInvoice",
          data: "uraian="+uraian+"&nilai="+nilai,
          success: function(response) {
            $("#invoice").html(response);
          }
        });
        $("#description").val("");
        $("#nilai").val("");
    });

    $('#addTrx').click(function () {
        var pos         = $("#pos").val();
        var description = $("#description").val();
        var nilai       = $("#nilai").val();

        $.ajax({
          type: 'POST',
          url: base_url+"/ajax/addTrx",
          data: "pos="+pos+"&description="+description+"&nilai="+nilai,
          success: function(response) {
            $("#transaction").html(response);
          }
        });
        $("#pos").val("");
        $("#description").val("");
        $("#nilai").val("");
    });    
 
    $('#addJurnalTrx').click(function () {
        var pos         = $("#pos").val();
        var jenis       = $("#jenis").val();
        var description = $("#description").val();
        var nilai       = $("#nilai").val();
        $.ajax({
          type: 'POST',
          url: base_url+"ajax/addJurnalTrx",
          data: "pos="+pos+"&jenis="+jenis+"&description="+description+"&nilai="+nilai,
          success: function(response) {
            $("#transaction").html(response);
          }
        });
        $("#pos").val("");
        $("#jenis").val("");
        $("#description").val("");
        $("#nilai").val("");
    });    
    $('#addSawal').click(function () {
        var pos         = $("#pos").val();
        var nilai       = $("#nilai").val();

        $.ajax({
          type: 'POST',
          url: base_url+"ajax/addSawal",
          data: "pos="+pos+"&nilai="+nilai,
          success: function(response) {
            $("#transaction").html(response);
          }
        });
        $("#pos").val("");
        $("#nilai").val("");
    });    
});

function deleteInvoice(idx){
    $.ajax({
      type: 'POST',
      url: base_url+"/ajax/deleteInvoice",
      data: "idx="+idx,  
      success: function(response) {
        $("#invoice").html(response);
      }
    });
}


function updateInvoice (idx){
    var uraian        = $("#description"+idx).val();
    var nilai        = $("#nilai"+idx).val();


    $.ajax({
      type: 'POST',
      url: base_url+"/ajax/updateInvoice",
      data: "idx="+idx+"&uraian="+uraian+"&nilai="+nilai,  
      success: function(response) {
        $("#invoice").html(response);
      }
    });
}

function setAccount(branch){
    var branch = branch.value;
    $.ajax({
      type: 'POST',
      url: base_url+"/ajax/setAccount",
      data: "branch="+branch,  
      success: function(response) {
        $("#account").html(response);
      }
    });
}

function setAccount2 (branch){
    var branch = branch.value;
    $.ajax({
      type: 'POST',
      url: base_url+"/ajax/setAccount",
      data: "branch="+branch,  
      success: function(response) {
        $("#account2").html(response);
      }
    });
}
function deleteTrx(idx){
    $.ajax({
      type: 'POST',
      url: base_url+"/ajax/deleteTrx",
      data: "idx="+idx,  
      success: function(response) {
        $("#transaction").html(response);
      }
    });
}


function updateTrx (idx){

    var pos         = $("#pos"+idx).val();
    var description = $("#description"+idx).val();
    var nilai       = $("#nilai"+idx).val();

    $.ajax({
      type: 'POST',
      url: base_url+"/ajax/updateTrx",
      data: "idx="+idx+"&pos="+pos+"&description="+description+"&nilai="+nilai,  
      success: function(response) {
        $("#transaction").html(response);
      }
    });
}

function deleteJurnalTrx(idx){
    $.ajax({
      type: 'POST',
      url: base_url+"/ajax/deleteJurnalTrx",
      data: "idx="+idx,  
      success: function(response) {
        $("#transaction").html(response);
      }
    });
}


function updateJurnalTrx (idx){

    var pos       = $("#pos"+idx).val();
    var jenis   = $("#jenis"+idx).val();
    var description = $("#description"+idx).val();
    var nilai       = $("#nilai"+idx).val();

    $.ajax({
      type: 'POST',
      url: base_url+"/ajax/updateJurnalTrx",
      data: "idx="+idx+"&pos="+pos+"&jenis="+jenis+"&description="+description+"&nilai="+nilai,  
      success: function(response) {
        $("#transaction").html(response);
      }
    });
}

function deleteSawal(idx){
    $.ajax({
      type: 'POST',
      url: base_url+"/ajax/deleteSawal",
      data: "idx="+idx,  
      success: function(response) {
        $("#transaction").html(response);
      }
    });
}


function updateSawal (idx){

    var pos         = $("#pos"+idx).val();
    var nilai       = $("#nilai"+idx).val();

    $.ajax({
      type: 'POST',
      url: base_url+"/ajax/updateSawal",
      data: "idx="+idx+"&pos="+pos+"&nilai="+nilai,  
      success: function(response) {
        $("#transaction").html(response);
      }
    });
}