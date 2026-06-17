//===============================================================================
$(window).on('scroll',function() {
    $('.card .sparkline').each(function() {
        var imagePos = $(this).offset().top;

        var topOfWindow = $(window).scrollTop();
        if (imagePos < topOfWindow + 400) {
            $(this).addClass("pullUp");
        }
    });
});

//===============================================================================

Morris.Area({
    element: 'm_area_chart2',
    data: [{
            period: '2012',
            SiteA: 52,
            SiteB: 0,

        }, {
            period: '2013',
            SiteA: 12,
            SiteB: 110,

        }, {
            period: '2014',
            SiteA: 125,
            SiteB: 68,

        }, {
            period: '2015',
            SiteA: 89,
            SiteB: 185,

        }, {
            period: '2016',
            SiteA: 175,
            SiteB: 58,

        }, {
            period: '2017',
            SiteA: 126,
            SiteB: 98,

        }
    ],
    xkey: 'period',
    ykeys: ['SiteA', 'SiteB'],
    labels: ['Site A', 'Site B'],
    pointSize: 2,
    fillOpacity: 0.2,
    pointStrokeColors: ['#3aabcb', '#85fd50'],
    behaveLikeLine: true,
    gridLineColor: '#4a555f',
    lineWidth: 1,
    smooth: false,
    hideHover: 'auto',
    lineColors: ['#3aabcb', '#85fd50'],
    resize: true

});

//===============================================================================
$(".dial1").knob();
$({animatedVal: 0}).animate({animatedVal: 66}, {
    duration: 4000,
    easing: "swing", 
    step: function() { 
        $(".dial1").val(Math.ceil(this.animatedVal)).trigger("change"); 
    }
});
$(".dial2").knob();
$({animatedVal: 0}).animate({animatedVal: 26}, {
    duration: 4500,
    easing: "swing", 
    step: function() { 
        $(".dial2").val(Math.ceil(this.animatedVal)).trigger("change"); 
    }
});
$(".dial3").knob();
$({animatedVal: 0}).animate({animatedVal: 76}, {
    duration: 3800,
    easing: "swing", 
    step: function() { 
        $(".dial3").val(Math.ceil(this.animatedVal)).trigger("change"); 
    }
});
$(".dial4").knob();
$({animatedVal: 0}).animate({animatedVal: 88}, {
    duration: 5200,
    easing: "swing", 
    step: function() { 
        $(".dial4").val(Math.ceil(this.animatedVal)).trigger("change"); 
    }
});