//income edit
  edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element)=>{
    element.addEventListener("click",(e)=>{
    // console.log('edit',);
    tr = e.target.parentNode.parentNode.parentNode;
    src = tr.getElementsByTagName("td")[1].innerText;
    amt = tr.getElementsByTagName("td")[2].innerText;
    indt = tr.getElementsByTagName("td")[0].innerText;
    freq = tr.getElementsByTagName("td")[3].innerText;
    // console.log(src);
    // console.log(amt);
    // console.log(indt);
    edtsource.value = src;
    edtamt.value = amt;
    edtindt.value = indt;
    edtfreq.value = freq;
    idedt.value = e.target.id;
    // console.log(e.target.id);
    $("#editmodal").modal('toggle');  
    })
  })


//expense edit
  edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element)=>{
    element.addEventListener("click",(e)=>{
    // console.log('edit',);
    tr = e.target.parentNode.parentNode.parentNode;
    cat = tr.getElementsByTagName("td")[1].innerText;
    amt = tr.getElementsByTagName("td")[2].innerText;
    descp = tr.getElementsByTagName("td")[3].innerText;
    expdt = tr.getElementsByTagName("td")[0].innerText;
          
    edtCat.value = cat;
    edtamt.value = amt;
    edtdescp.value = descp;
    edtexpdt.value = expdt;
    idedt.value = e.target.id;
    // console.log(e.target.id);
    $("#editmodal").modal('toggle'); 
    })
  })

//delete income
  function loadincome()
    {
      $("#incometable").load("income_table.php");
    }

  function fun1(id)
    {
      // alert(id);
      let text = "Do you want to delete this income";
      if (confirm(text) == true) {
      $.ajax({url: "delincome.php?id="+id, success: function(result){
        // alert(result);
          loadincome();
        }});
      } 
        return false;
    }


//delete expense
  function loadexpense()
  {
    $("#expensetable").load("expense_table.php");
  }

  function fun2(id)
  {
    // alert(id);
    let text = "Do you want to delete this expense";
    if (confirm(text) == true) {
      $.ajax({url: "delexpense.php?id="+id, success: function(result){
        // alert(result);
        loadexpense();
      }});
    } 
      return false;
  }

     
function validateFrm()
{
    var name = document.getElementById("name").value.trim();
    var email = document.getElementById("email").value.trim();
    var pass = document.getElementById("pass").value.trim();
    var cpass = document.getElementById("cpass").value.trim();

    if(name ==="" || email ==="" || pass ==="" || cpass ==="")
    {
        alert("All feilds are required");
        return false;
    }
    if(pass !== cpass)
    {
       alert("Passwords mismatch");
        return false;
    }

        return true;
   
}

//for back button session destroy
window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});