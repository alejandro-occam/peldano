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
};
