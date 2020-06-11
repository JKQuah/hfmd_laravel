//creating chart instance
var chart = am4core.create(
    "piechartdiv",
    am4charts.PieChart
);

// create piechart data
chart.data = [{
    "gender": "male",
    "cases": 200000
}, {
    "gender": "female",
    "cases": 350000
}];

var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "cases";
pieSeries.dataFields.category = "gender";