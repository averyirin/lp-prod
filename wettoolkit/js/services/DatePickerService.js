var DatePicker = function() {
        this.dateOptions = {
            formatYear: 'yy',
            maxDate: new Date(2020, 5, 22),
            minDate: new Date(),
            startingDay: 1
        };
        this.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
        this.format = this.formats[0];
        this.altInputFormats = ['M!/d!/yyyy'];
        this.popup1 = {};
        this.popup2 = {};
        
        this.open1 = function() {
            this.popup1.opened = true;
        }
        
        this.open2 = function() {
            this.popup2.opened = true;
        }
        
}

angular.module('portal').factory('DatePicker', function(){
    return DatePicker;
});