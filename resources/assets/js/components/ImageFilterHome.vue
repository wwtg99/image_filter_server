<template>
    <div class="main">
        <el-row class="first_block" type="flex">
            <el-col :span="24">
                <h1>52jing.wang</h1>
                <p class="descr">图片处理与滤镜工具</p>
                <p class="descr"><small>仅供娱乐（^o^）</small></p>
                <p><el-button round @click="handleGithub">GITHUB</el-button></p>
            </el-col>
        </el-row>
        <div class="second_block">
            <el-row type="flex">
                <el-col :span="8" :offset="8" class="text-center">
                    <button class="input_bt" title="选择一张图片" @click="scrollToUse"><i class="el-icon-plus"></i></button>
                </el-col>
            </el-row>
            <div class="text-center">
                <h2>Example</h2>
            </div>
            <div class="text-center">
                <h3>原图</h3>
                <div><img src="/images/examples/origin.jpg"/></div>
            </div>
            <div class="image_list">
                <image-list :images="examples"></image-list>
            </div>
        </div>
        <div class="third_block">
            <image-filter-tool></image-filter-tool>
        </div>
        <div class="forth_block">
            <el-row>
                <el-col :span="6" :offset="5">
                    <div class="thx_block">
                        <img src="../../images/python.png">
                    </div>
                </el-col>
                <el-col :span="6" :offset="2">
                    <div class="thx_block">
                        <img src="../../images/pillow.png">
                    </div>
                </el-col>
            </el-row>
        </div>
        <footer class="foot">
            <p class="t1">52jing.wang</p>
            <p class="t2">Copyright © wwtg99</p>
        </footer>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                examples: [
                    {"title": "Blur 模糊", "descr": "使图像变得模糊。", "src": "/images/examples/blur.jpg"},
                    {"title": "Contour 描边", "descr": "描出图像的边缘。", "src": "/images/examples/contour.jpg"},
                    {"title": "Edge Curve 边缘雕刻", "descr": "找到图像的边缘，类雕刻风格。", "src": "/images/examples/edge_curve.jpg"},
                    {"title": "Edge Enhance 边缘强化", "descr": "强化边缘线条。", "src": "/images/examples/edge_enhance.jpg"},
                    {"title": "Emboss 浮雕", "descr": "类浮雕风格。", "src": "/images/examples/emboss.jpg"},
                    {"title": "Emboss 45d 45 度浮雕", "descr": "45 度光源彩色浮雕风格。", "src": "/images/examples/emboss_45d.jpg"},
                    {"title": "Emboss Asymmetric 非对称浮雕", "descr": "非对称浮雕风格。", "src": "/images/examples/emboss_asym.jpg"},
                    {"title": "Gaussian Blur 高斯模糊", "descr": "高斯模糊，半径 2。", "src": "/images/examples/gaussian_blur.jpg"},
                    {"title": "Grey Scale 灰度", "descr": "灰度图。", "src": "/images/examples/grey.jpg"},
                    {"title": "Hand Drawn 手绘", "descr": "类手绘风格。", "src": "/images/examples/hand_drawn.jpg"},
                    {"title": "Max Filter 最大值滤镜", "descr": "取给定矩形范围内的最大值。", "src": "/images/examples/max.jpg"},
                    {"title": "Median Filter 中值滤镜", "descr": "取给定矩形范围内的中值。", "src": "/images/examples/median.jpg"},
                    {"title": "Min Filter 最小值滤镜", "descr": "取给定矩形范围内的最小值。", "src": "/images/examples/min.jpg"},
                    {"title": "Mode Filter 最常值滤镜", "descr": "取给定矩形范围内的最常出现的值。", "src": "/images/examples/mode.jpg"},
                    {"title": "Sharpen 锐化", "descr": "锐化图像。", "src": "/images/examples/sharpen.jpg"},
                    {"title": "Sharp Center 中心锐化", "descr": "中心锐化图像。", "src": "/images/examples/sharp_center.jpg"},
                    {"title": "Sharp Edge 边缘锐化", "descr": "边缘锐化图像。", "src": "/images/examples/sharp_edge.jpg"},
                    {"title": "Smooth 平滑", "descr": "平滑图像。", "src": "/images/examples/smooth.jpg"},
                    {"title": "Unsharp Mask 锐化遮罩", "descr": "半径 2，150%，最小阈值 3，参考 <a href='https://en.wikipedia.org/wiki/Unsharp_masking#Digital_unsharp_masking' target='_blank'>WiKi</a>。", "src": "/images/examples/unsharp_mask.jpg"}
                ]
            }
        },
        methods: {
            scrollToUse() {
                let h = document.querySelector('.third_block').offsetTop;
                let ch = 0;
                if (document.documentElement && document.documentElement.scrollTop) {
                    ch = document.documentElement.scrollTop;
                } else if (document.body) {
                    ch = document.body.scrollTop;
                }
                let t = setInterval(function() {
                    if (ch < h) {
                        ch += 10;
                        window.scrollTo(0, ch);
                    } else {
                        clearInterval(t);
                    }
                }, 1);
            },
            handleGithub() {
                window.open('https://github.com/wwtg99/image_filter_server');
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style scoped>
    bodu {
        transition: all 1s;
    }
    .main {
        height: 100%;
    }
    .first_block {
        height: 300px;
        background-color: #FFE037;
        text-align: center;
    }
    .first_block h1 {
        color: #23033C;
        text-align: center;
        padding: 20px 0;
    }
    .first_block .descr {
        text-align: center;
    }
    .second_block {
        background-color: #1DCD9F;
        text-align: center;
    }
    .input_bt {
        position: relative;
        background-color: rgba(8,140,111,0.8);
        border: none;
        outline: none;
        padding: 0;
        margin: 0;
        height: 60px;
        width: 60px;
        cursor: pointer;
        transition: .8s;
        border-radius: 50%;
        color: #fff;
        z-index: 10;
        transform: translateY(-50%);
        text-align: center;
        font-size: 28px;
    }
    .input_bt:hover {
        background-color: rgba(8,140,111,1);
    }
    .input_bt i {
        transition: .8s;
        color: white;
    }
    .input_bt i:hover {
        transform: rotate(90deg);
    }
    .third_block {
        padding: 20px 0 60px 0;
    }
    .forth_block {
        padding: 40px 0 0 0;
        background: -webkit-linear-gradient(top, #373737, #212121);
    }
    .forth_block .thx_block {
        
    }
    .forth_block .thx_block img {
        width: 100%;
    }
    .foot {
        padding: 20px;
        background-color: #212121;
        color: #999999;
        text-align: center;
    }
    .foot .t1 {
        font-size: 20px;
    }
    .foot .t2 {
        font-size: 14px;
    }
</style>
