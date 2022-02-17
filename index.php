<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="styles/glDatePicker.default.css" rel="stylesheet" type="text/css">
    <title>Document</title>
</head>
<body>

<input type="text" id="example1" gldp-id="mydate" />
<div gldp-el="mydate"
     style="width:400px; height:300px; position:absolute; top:70px; left:100px;">
</div>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="glDatePicker.min.js"></script>

<script type="text/javascript">
    $(window).load(function()
    {
        $(function(){
            $.ajax({
                type:'GET',
                url: 'indexBack.php',
                success: function (data){
                    console.log('success', data);
                    let arr = [];
                    const n = 5;
                    for (let i = 0; i<30; i++){
                        if (data[i]<n){
                            arr.push(1)
                        } else {
                            arr.push(0)
                        }
                    }
                    console.log(arr);
                    let frontArr = [];

                    for (let k = 0; k<arr.length; k++){
                        let date1 = new Date();
                        if (arr[k] == 1){frontArr.push({ 'date': new Date(date1.setDate(date1.getDate() + k)) })}
                    }

                    $('#example1').glDatePicker({
                        showAlways: true,
                        dowOffset: 1,
                        selectableDates: frontArr,
                        onClick: (function(el, cell, date, data) {
                            el.val(date.toLocaleDateString());
                        }),
                    });

                    return arr;
                }
            });
        });


    });

</script>
</body>
</html>
