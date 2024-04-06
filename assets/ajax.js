
//var base_url = window.location.origin;
//var getUrl = window.location;
//var base_url =  getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[0];
var getUrl = window.location;
var base_url = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[0] +"erpe/";

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
        var jnstrx      = $("#jnstrx").val();
        var pos         = $("#pos").val();
        var description = $("#description").val();
        var nilai       = $("#nilai").val();


        $.ajax({
          type: 'POST',
          url: base_url+"ajax/addTrx",
          data: "pos="+pos+"&description="+description+"&nilai="+nilai+"&jnstrx="+jnstrx,
          success: function(response) {
            $("#transaction").html(response);
          }
        });
        $("#pos").val("");
        $("#description").val("");
        $("#nilai").val("");
    });

    $('#addTsk').click(function () {
        var event       = $("#event").val();
        var urutan       = $("#urutan").val();
        var desc       = $("#desc").val();
        var kpi      = $("#kpi").val();
        var ppn        = $("#ppn").val();
        var prshn      = $("#prshn").val();
        var keter        = $("#keter").val();
        var qty       = $("#qty").val();
        var satqty       = $("#satqty").val();
        var freq      = $("#freq").val();
        var satfreq        = $("#satfreq").val();
        var price       = $("#price").val();


        $.ajax({
          type: 'POST',
          url: base_url+"ajax/addTsk",
          data: "event="+event+"&urutan="+urutan+"&kpi="+kpi+"&desc="+desc+"&ppn="+ppn+"&prshn="+prshn+"&keter="+keter+"&qty="+qty+"&satqty="+satqty+"&freq="+freq+"&satfreq="+satfreq+"&price="+price,
          success: function(response) {
            $("#tasks").html(response);
          }
        });

        urutan++;
        $("#event").val("");
        $("#kpi").val(kpi);
        $("#urutan").val(urutan);
        $("#desc").val("");
        $("#ppn").val("");
        $("#prshn").val("");
        $("#keter").val("");
        $("#qty").val("");
        $("#satqty").val(satqty);
        $("#freq").val("");
        $("#satfreq").val(satfreq);
        $("#price").val("");
    });

    $('#addTskEmployee').click(function () {
        var task       = $("#task").val();
        var kpi      = $("#kpi").val();
        var ket        = $("#ket").val();
        var start       = $("#start").val();
        var end       = $("#end").val();
        var kendala       = $("#kendala").val();


        $.ajax({
          type: 'POST',
          url: base_url+"ajax/addTskEm",
          data: "task="+task+"&kpi="+kpi+"&ket="+ket+"&start="+start+"&end="+end+"&kendala="+kendala,
          success: function(response) {
            $("#tasks").html(response);
          }
        });
        $("#task").val("");
        $("#kpi").val("");
        $("#ket").val("");
        $("#start").val("");
        $("#end").val("");
        $("#kendala").val("");
    }); 

    $('#showprojectid').change(function () {
        $.ajax({
          type: 'POST',
          url: base_url+"/ajax/showproject",
          data: "idx="+$(this).val(),  
          success: function(response) {
            $("#showproject").html(response);
          }
        });
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

function deleteTsk(idx){
    $.ajax({
      type: 'POST',
      url: base_url+"/ajax/deleteTsk",
      data: "idx="+idx,  
      success: function(response) {
        $("#tasks").html(response);
      }
    });
}

function updateTsk (idx){

    var event       = $("#event"+idx).val();
    var kpi      = $("#kpi"+idx).val();
    var urutan      = $("#urutan"+idx).val();
    var desc        = $("#desc"+idx).val();
    var ppn       = $("#ppn"+idx).val();
    var prshn        = $("#prshn"+idx).val();
    var keter       = $("#keter"+idx).val();
    var qty       = $("#qty"+idx).val();
    var satqty      = $("#satqty"+idx).val();
    var freq        = $("#freq"+idx).val();
    var satfreq       = $("#satfreq"+idx).val();
    var price       = $("#price"+idx).val();
    var jumlah      = $("#jumlah").val();
    //alert(jumlah);

    $.ajax({
      type: 'POST',
      url: base_url+"/ajax/updateTsk",
      data: "idx="+idx+"&event="+event+"&urutan="+urutan+"&desc="+desc+"&ppn="+ppn+"&kpi="+kpi+"&prshn="+prshn+"&keter="+keter+"&qty="+qty+"&satqty="+satqty+"&freq="+freq+"&satfreq="+satfreq+"&price="+price,
      success: function(response) {
        $("#tasks").html(response);
      }
    });

    $("#urutan").val(jumlah);
}

function asaveTsk (idx){

    var event       = $("#event"+idx).val();
    var kpi      = $("#kpi"+idx).val();
    var urutan      = $("#urutan"+idx).val();
    var desc        = $("#desc"+idx).val();
    var ppn       = $("#ppn"+idx).val();
    var prshn        = $("#prshn"+idx).val();
    var keter       = $("#keter"+idx).val();
    var qty       = $("#qty"+idx).val();
    var satqty      = $("#satqty"+idx).val();
    var freq        = $("#freq"+idx).val();
    var satfreq       = $("#satfreq"+idx).val();
    var price       = $("#price"+idx).val();

    $.ajax({
      type: 'POST',
      url: base_url+"/ajax/asaveTsk",
      data: "idx="+idx+"&event="+event+"&urutan="+urutan+"&desc="+desc+"&ppn="+ppn+"&prshn="+prshn+"&keter="+keter+"&kpi="+kpi+"&qty="+qty+"&satqty="+satqty+"&freq="+freq+"&satfreq="+satfreq+"&price="+price,
      success: function(response) {
        $("#tasks").html(response);
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