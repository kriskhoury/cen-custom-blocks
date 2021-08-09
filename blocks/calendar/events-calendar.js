( function() {
  var cal = new Vue({
    el: document.getElementById('calendar-app'),
    data: {
      apiEndPoint: '/wp-json/v1/events',
      months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
      currentMonthID: 0,
      currentYearID: 0,
      events: [],
    },
    computed:{
      currentMonth(){
        return this.months[this.currentMonthID];
      },
      monthLeading(){
        const month = this.currentMonthID+1;
        if(month.toString().length == 1){
          return '0'+month;
        }else{
          return month.toString();
        }
      },
    },
    created(){
      const d = new Date();
      this.currentMonthID = d.getMonth();
      this.currentYearID = d.getFullYear();
      this.getEvents();
    },
    methods:{
      prevClick(){
        if(this.currentMonthID > 0){
          this.currentMonthID--;
        }else{
          this.currentMonthID = this.months.length-1;
        }
        this.getEvents();
      },
      nextClick(){
        if(this.currentMonthID < this.months.length-1){
          this.currentMonthID++;
        }else{
          this.currentMonthID = 0;
        }
        this.getEvents();
      },
      addDays(date, days){
        const copy = new Date(date);
        copy.setDate(date.getDate() + days);
        return copy;
      },
      getEvents(){
        const requestData = {
          'month': this.monthLeading,
          'year': this.currentYearID.toString()
        }
        const requestOptions = {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(requestData)
        };
        fetch(this.apiEndPoint, requestOptions)
          .then((r) => r.json())
          .then((res) => this.events = res.map(x => x))
      },
      getDayOfWeekName(num){
        return isNaN(num) ? null : ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][num];
      },
      getDayOfWeek(date,days) {
        days = Number(days-1);

        const setDate = new Date(date);
        const firstDOW = setDate.getDay();
        const secondDOW = this.addDays(setDate, days).getDay();

        const firstDayAbbr = this.getDayOfWeekName(firstDOW);
        const secondDayAbbr =  this.getDayOfWeekName(secondDOW);

        if(days == null || days == 0 || days == NaN){
          return firstDayAbbr;
        }else{
          return firstDayAbbr + '-' + secondDayAbbr;
        }  
      },
      getDayOfMonth(date,days) {
        days = Number(days-1);

        const setDate = new Date(date);
        const firstDOW = setDate.getDate();
        const secondDOW = this.addDays(setDate, days).getDate();

        if(days == null || days == 0 || days == NaN){
          return this.getSuffix(firstDOW);
        }else{
          return this.getSuffix(firstDOW) + '-' + this.getSuffix(secondDOW);
        }  
      },
      getClasses(obj){
        if(obj == null){
          return 'type-general';
        }else{
          return 'type-'+obj.slug;
        }
      },
      getSuffix(i) {
        var j = i % 10, k = i % 100;
        if (j == 1 && k != 11) {
          return i + "st";
        }
        if (j == 2 && k != 12) {
          return i + "nd";
        }
        if (j == 3 && k != 13) {
          return i + "rd";
        }
        return i + "th";
      }
    },
  });
})();