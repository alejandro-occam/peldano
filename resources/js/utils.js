export const func = {
    roundAndFix(num, decimals = 2) {
        num = Math.round(num * Math.pow(10, decimals)) / Math.pow(10, decimals);
        num = num.toFixed(decimals);

        return num;
    },
    numberWithDotAndComma(number) {
        number = number.toString().replace(".", ",");
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },

    //Consultar la fecha actual
    getNow() {
        const today = new Date();
        var day = today.getDate();
        if(day < 10){
            day = '0' + today.getDate();
        }
        var month = (today.getMonth()+1);
        if(month < 10){
            month = '0' + (today.getMonth()+1)
        }
        const date = day + '-' + month + '-' + today.getFullYear();
        return date;
    },

    //Formate Date Calendar
    customFormDate(date) {
        var new_date = new Date(date);
        var new_date2 = new_date.toLocaleString('en-GB')
        var new_date3 = new_date2.split(', ');
        var new_date4 = new_date3[0].split('/');
        var finish_date = new_date4[0] + '-' + new_date4[1] + '-' + new_date4[2];
        return finish_date;
    },

    //Devolvemos el el mes en letras de la fecha
    changeFormatDate(date){
        var date_aux = date.split('-');
        var new_date = date_aux[1] + '-' + date_aux[0] + '-' +  date_aux[2];
        var mydate = new Date(new_date);
        var month = ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN",
        "JUL", "AGO", "SEP", "OCT", "NOV", "DEC"][mydate.getMonth()];
        var str = month + mydate.getYear().toString().substr(-2);
        return str;
    }
};
