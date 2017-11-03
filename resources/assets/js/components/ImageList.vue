<template>
    <div class="image_list">
        <div class="title"><h3>{{ title }}</h3></div>
        <figure>
            <img v-for="(img, index) in images" :src="img.src" :alt="img.title" :key="index">
        </figure>
        <div class="descr" v-html="descr"></div>
        <nav>
            <button class="nav_btn nav_left" @click="currImage--; rotateCarousel()"><i class="el-icon-arrow-left"></i></button>
            <button class="nav_btn nav_right" @click="currImage++; rotateCarousel()"><i class="el-icon-arrow-right"></i></button>
        </nav>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                currImage: 0,
                figure: null,
                num: 0,
                gap: 20,
                theta: 0,
                title: '',
                descr: ''
            }
        },
        props: {
            images: { //{title:'', descr:'', src:''}
                type: Array,
                required: true
            }
        },  
        methods: {
            setupCarousel(figure, gap) {
                let images = figure.children;
                this.num = images.length;
                this.theta = 2 * Math.PI / this.num;
                let s = parseFloat(getComputedStyle(images[0]).width);

                let apothem = s / (2 * Math.tan(Math.PI / this.num));
                
                figure.style.transformOrigin = '50% 50% ' + -apothem + 'px';

                for (var i = 0; i < this.num; i++) {
                    images[i].style.padding = gap + 'px';
                }
                for (var i = 1; i < this.num; i++) {
                    images[i].style.transformOrigin = '50% 50% ' + -apothem + 'px';
                    images[i].style.transform = 'rotateY(' + i * this.theta + 'rad)';
                }
                for (var i = 0; i < this.num; i++) {
                    images[i].style.backfaceVisibility = 'hidden';
                }
                this.rotateCarousel(this.currImage);
            },
            rotateCarousel(imageIndex) {
                if (isNaN(imageIndex)) {
                    imageIndex = this.currImage;
                }
                this.figure.style.transform = 'rotateY(' + imageIndex * -this.theta + 'rad)';
                while (imageIndex < 0) {
                    imageIndex += this.num;
                }
                while (imageIndex >= this.num) {
                    imageIndex -= this.num
                }
                this.title = this.images[imageIndex].title;
                this.descr = this.images[imageIndex].descr;
            }
        },
        mounted() {
            this.figure = this.$el.querySelector('figure');
            this.setupCarousel(this.figure, this.gap);
        }
    }
</script>

<style scoped>
    .image_list {
        padding: 20px;
        -webkit-perspective: 500px;
        perspective: 500px;
        overflow: hidden;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }
    .image_list > * {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
    }
    .image_list .title {
        

    }
    .image_list .descr {
        text-align: center;
        word-wrap: normal;
        overflow: hidden;
        padding: 10px 0;
    }
    .image_list figure {
        margin: 0;
        width: 40%;
        -webkit-transform-style: preserve-3d;
        transform-style: preserve-3d;
        -webkit-transition: -webkit-transform 0.5s;
        transition: -webkit-transform 0.5s;
        transition: transform 0.5s;
        transition: transform 0.5s, -webkit-transform 0.5s;
    }
    .image_list figure img {
        width: 100%;
        box-sizing: border-box;
        padding: 0 0px;
    }
    .image_list figure img:not(:first-of-type) {
        position: absolute;
        left: 0;
        top: 0;
    }
    .image_list nav {
        
    }
    .nav_btn {
        border: none;
        outline: none;
        padding: 0;
        margin: 0;
        height: 36px;
        width: 36px;
        cursor: pointer;
        transition: .3s;
        position: absolute;
        top: 50%;
        border-radius: 50%;
        background-color: rgba(31,45,61,.11);
        color: #fff;
        z-index: 10;
        transform: translateY(-50%);
        text-align: center;
        font-size: 12px;
    }
    .nav_btn:hover {
        background-color: rgba(31,45,61,.51);
    }
    .nav_left {
        left: 16px;
    }
    .nav_right {
        right: 16px;
    }
</style>
