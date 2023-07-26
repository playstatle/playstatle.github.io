function getUserProfile(userId){
    axios
      .get("./controller/SearchController.php?action=getUserProfile&userId="+userId+"")
      .then(function (response) {
        // console.log(response.data);

        let user = response.data;
  
        user.user_type = user.user_type.toUpperCase();
        searchForm(user.user_type);
      });
}
  
function searchForm(userType){
    if (userType == "UTAC" || userType == "UTACG"){

        axios
            .get("./controller/SearchController.php?action=getCustomerCode")
            .then(function (response) {
                // console.log(response.data);
                let customerCode = "";

                response.data.forEach(function(value){
                    customerCode += '<option value="'+value.code+'">'+value.code+'</option>';
                });

                $('#form').append( ' \
                    <div> \
                        <label for="customer" id="label_customer">Customer Code:</label> \
                        <select id="customer_code" name="customer_code"> \
                            <option value="" disabled selected>Select code</option> \
                            '+customerCode+' \
                        </select> \
                    </div> \
                    </br> \
                    <div> \
                        <label for="customer" id="label_customer">Customer Eng. Reg/Cust Assy lot/PO#:</label> \
                        <select id="customer" name="customer"> \
                            <option value="" disabled selected>Select your role</option> \
                        </select> \
                    </div> \
                    </br> \
                ')
            });

    } else {
        $('#form').append( ' \
            <div> \
                <label for="customer" id="label_customer">Customer Eng. Reg/Cust Assy lot/PO#:</label> \
                <select id="customer" name="customer"> \
                    <option value="" disabled selected>Select your role</option> \
                </select> \
            </div> \
            </br> \
        ')
    }
}

function search(userId){
    // Authentication userId before search.

    let dateForm = document.getElementById("date_from").value;
    let dateTo = document.getElementById("date_to").value;
    let customerCode = document.getElementById("customer_code").value;

    let form = {
        action: "search",
        dateFrom: dateForm,
        dateTo: dateTo,
        customerCode: customerCode,
    };
    console.log(form);

    axios
      .post("./controller/SearchController.php", form)
      .then(function (response) {
        // console.log(response.data);

        let body = "";
        response.data.forEach(function(value){
            body += "";
        });

        $("#search").append(" \
          <table id='search-table' class='table table-striped table-bordered nowrap' style='width: 100%'> \
              <thead> \
                  <tr> \
                      <th>NO.</th> \
                      <th>Kilometers</th> \
                      <th>Uploaded At</th> \
                  </tr> \
              </thead> \
              <tbody> \
              </tbody> \
          </table> \
        ");

        $("#search-table").DataTable({
            responsive: true,
            lengthChange: false
        });
      });
}