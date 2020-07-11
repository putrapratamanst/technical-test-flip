function getList() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let response = JSON.parse(this.responseText);

            if (!empty(response) && response.success && !empty(response.data)) {
                var table = "";

                document.getElementById("table").innerHTML =
                    "<tr>" +
                    "<th>REF ID</th>" +
                    "<th>Bank Code</th>" +
                    "<th>Account Number</th>" +
                    "<th>Amount</th>" +
                    "<th>Remark</th>" +
                    "<th>Fee</th>" +
                    "<th>Receipt</th>" +
                    "<th>Time Served</th>" +
                    "<th>Beneficiary Name</th>" +
                    "<th>Status</th>" +
                    "</tr>";

                for (let i in response.data) {
                    let data = response.data[i];

                    table +=
                        "<tr>" +
                        "<td>" + data.disbursed_id + "</td>" +
                        "<td>" + data.bank_code + "</td>" +
                        "<td>" + data.account_number + "</td>" +
                        "<td>" + data.amount + "</td>" +
                        "<td>" + data.remark + "</td>" +
                        "<td>" + data.fee + "</td>" +
                        "<td><a href='" + data.receipt + "'>Open</a></td>" +
                        "<td>" + data.time_served + "</td>" +
                        "<td>" + data.beneficiary_name + "</td>" +
                        "<td>" + data.status + "</td>" +
                        "</tr>";
                }

                document.getElementById("table").innerHTML += table;
            }

        }
    };
    xmlhttp.open("GET", "/list", true);
    xmlhttp.send();
}

function empty($data) {
    if (typeof $data == "undefined") {
        return true;
    }

    switch ($data) {
        case null:
            return true;
        case {}:
            return true;
        case []:
            return true;
        case "":
            return true;
    }

    return false;
}


getList()
