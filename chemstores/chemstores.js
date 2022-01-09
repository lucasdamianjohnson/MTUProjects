
function totalCost() {
  var total = 0.0;
  $.each($(".totalTextboxes"),function(index,val) {
    total += ($(val).val()*1);
  });
  $("#estimatedTotalBox").val(total);
}

function calcCost(row) {
  $("#tcost"+row).val($("#qty"+row).val() * $("#ucost"+row).val());
  totalCost();
}

defer(function(){

  var adds = 9;
  $.validator.addMethod("customEmail", function(value, element) {
   return this.optional( element ) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@mtu.edu$/.test( value );
 }, 'Please enter a valid MTU email address.');


  $( "#requester_email" ).rules("add",{
    required: true,
    customEmail: true
  });



  $("#add_more").on("click", function(event){
    event.preventDefault();
    if(adds!=40){
      adds++;
      var copy = $("table.storetable tr:last").prev().clone();
      $(copy).find("input").each(function(){
       var id = this.id;
       //var name = this.name;
       var minus = 1;
       if( adds > 10){
        minus = 2;
      }
      id = id.substring(0,id.length - minus);

     // name = name.substring(0,name.length - minus);
      if(id != "tcost") {
        this.value = "";
      } else {
        this.value = 0;
      }
      if(id != "") {
        this.id = id + adds;
      }
     // if(name != "") {
    //    this.name = name + adds;
    //  }
    });
      $(copy).insertAfter($("table.storetable tr:last").prev());


    }

  });


  $("table.storetable ").on("change","input", function(event) {
   var row = this.id.match(/\d+/)[0];
   calcCost(row);
 });

});


