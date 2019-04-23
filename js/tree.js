window.onload = function () {
    var container = document.getElementsByTagName('body')[0];
    container.innerHTML += '<div id="tree" style="height:100%;background:rgba(255, 255, 255, 0.7);"></div>';
    // eCharts
    var dom = document.getElementById("tree");
    var myChart = echarts.init(dom, null, { renderer: 'svg' });
    option = null;
    myChart.showLoading();
    myChart.hideLoading();
    myChart.setOption(option = {
        tooltip: {
            trigger: 'item',
            triggerOn: 'mousemove'
        },
        series: [
            {
                type: 'tree',
                data: [data],
                left: '2%',
                right: '2%',
                top: '8%',
                bottom: '20%',
                symbol: 'emptyCircle',
                orient: 'vertical',
                roam: true,
                expandAndCollapse: true,
                label: {
                    normal: {
                        position: 'insideBottom',
                        offset: [0, -50],
                        rotate: 0,
                        verticalAlign: 'middle',
                        align: 'right',
                        fontSize: 15
                    }
                },
                leaves: {
                    label: {
                        normal: {
                            position: 'insideBottomRight',
                            offset: [50, 0],
                            rotate: -45,
                            verticalAlign: 'middle',
                            align: 'left',
                            fontSize: 15
                        }
                    }
                },
                initialTreeDepth: -1,
                animationDurationUpdate: 750
            }
        ]
    })
    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }

    // 获取目标元素
    var target = null;
    var tspan = document.getElementsByTagName('tspan');
    for (var i = 0; i < tspan.length; i++) {
        if (tspan[i].innerHTML == user) {
            tspan[i].setAttribute('class', 'target');
            break;
        }
    }
    target = document.getElementsByClassName('target')[0];
    try {
        target.setAttribute('fill', 'transparent');
        target.setAttribute('stroke', 'transparent');
        flash();
    } catch (e) { }
    
    // 当前查询用户闪动
    function flash() {
        var flag = true;
        setInterval(function () {
            if (flag) {
                target.setAttribute('fill', 'transparent');
                target.setAttribute('stroke', 'transparent');
            } else {
                target.setAttribute('fill', '#e54d26');
                target.setAttribute('stroke', '#e54d26');
            }
            flag = ! flag;
        }, 500);
    }
}