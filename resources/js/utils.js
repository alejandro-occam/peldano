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
        const date = today.getDate() + '-' + (today.getMonth()+1)+ '-' + today.getFullYear();
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
    }
};
