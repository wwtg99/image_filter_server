<template>
	<div>
		<div class="imageflow" :style='{width: width}'>
			<img v-for="(image, index) in images" :src="image.src" :data-info="image.title" :style="imageStyles[index]" v-show="isShow(index)" @click="handleClick(index)">
			<div class="title" :style="titleStyle">{{ title }}</div>
		</div>
		<el-dialog :title="detailTitle" :visible.sync="showDetail" custom-class="detail" width="80%">
			<div><img :src="detailImg"></div>
		</el-dialog>
	</div>
</template>

<script>
export default {
	name: 'imageflow',
	data () {
		return {
			title: '',
			curIndex: 0,
			showDetail: false,
			detailTitle: '',
			detailImg: ''
		}
	},
	computed: {
		imageNum() {
			return this.images.length;
		},
		imageStyles() {
			let styles = [];
			let mleft = (this.width - this.imageWidth) * 0.5;
			for (var i = 0; i < this.imageNum; i++) {
				styles[i] = {
					'filter': 'brightness(0.65)',
				};
				//left side of current
				if ((this.curIndex - this.showSize) <= i && i < this.curIndex) {
					styles[i]['z-index'] = i + 100;
					styles[i]['left'] = mleft - (this.curIndex - i) * this.imageSpace + 'px';
					styles[i]['perspective'] = 300;
					let deg = Math.max(Math.min(((this.curIndex - i) * 10 + 45), 90), -90);
					let z = -(this.curIndex - i) * 30 - 25;
					styles[i]['transform'] = 'rotateY(' + deg + 'deg) translateZ(' + z + 'px)';
				}
				//right side of current
				else if (i > this.curIndex && i <= (this.curIndex + this.showSize)) {
					styles[i]['z-index'] = 2 * this.curIndex - i + 100;
					styles[i]['left'] = mleft + (i - this.curIndex) * this.imageSpace + 'px';
					styles[i]['perspective'] = 300;
					let deg = Math.max(Math.min(((this.curIndex - i) * 10 - 45), 90), -90);
					let z = (this.curIndex - i) * 30 - 25;
					styles[i]['transform'] = 'rotateY(' + deg + 'deg) translateZ(' + z + 'px)';
				}
				//hidden
				else {
					styles[i]['z-index'] = 1;
					styles[i]['left'] = mleft + 'px';
				}
			}
			//current image
			styles[this.curIndex]['visibility'] = 'visible';
			styles[this.curIndex]['filter'] = 'none';
			styles[this.curIndex]['left'] = mleft + 'px';
			styles[this.curIndex]['z-index'] = this.imageNum + 100;
			styles[this.curIndex]['perspective'] = 0;
			styles[this.curIndex]['transform'] = 'rotateY(0deg) translateZ(0px)';
			this.title = this.images[this.curIndex].title;
			return styles;
		},
		titleStyle() {
			return {
				width: this.imageWidth + 'px', 
				left: (this.width - this.imageWidth) * 0.5 + 'px'
			};
		}
	},
	props: {
		images: {
			type: Array,
			required: true
		},
		width: {
			type: Number,
			default: 1000
		},
		index: {
			type: Number,
			default: -1  // use center image
		},
		showSize: {
			type: Number,
			default: 4
		},
		imageWidth: {
			type: Number,
			default: 300
		},
		imageSpace: {
			type: Number,
			default: 50
		},
	},
	methods: {
		init() {
			if (this.index < 0) {
				this.curIndex = parseInt(this.imageNum / 2);
			} else {
				this.curIndex = this.index;
			}
		},
		isShow(index) {
			if (index >= (this.curIndex - this.showSize) && index <= this.curIndex + this.showSize) {
				return true;
			} else {
				return false;
			}
		},
		handleClick (index) {
			if (index == this.curIndex) {
				this.detailTitle = this.images[this.curIndex].title;
				this.detailImg = this.images[this.curIndex].src;
				this.showDetail = true;
			} else {
				this.curIndex = index;
			}
		},
	},
	created() {
		this.init();
	}
}
</script>

<style scoped>
	.imageflow {
		margin: 0 auto;
		padding-top: 30px;
		cursor: pointer;
		overflow: hidden;
		background-color: #fff;
		min-height: 300px;
		perspective: 600px;
		transform: rotateY(0deg) translateZ(-5px);
		-ms-transform: rotateY(0deg) translateZ(-5px);
		-moz-transform: rotateY(0deg) translateZ(-5px);
		-webkit-transform: rotateY(0deg) translateZ(-5px);
		-o-transform: rotateY(0deg) translateZ(-5px);
	}
	.imageflow > img {
		position: absolute;
		height: auto;
		bottom: 60px;
		box-shadow: rgba(0, 0, 0, 0.298039) 0px 10px 30px;
    	transition: all 0.4s ease;
		-moz-transition: all 0.4s ease;
		-webkit-transition: all 0.4s ease;
		-o-transition: all 0.4s ease;
	}
	.imageflow .title {
		position: absolute;
		padding: 3px;
		height: 20px;
		line-height: 20px;
		font-size: 14px;
		padding: 0px 3px;
		color: rgb(34, 34, 34);
		background: rgb(221, 221, 221);
		border-radius: 10px;
		font-weight: normal;
		bottom: 28px;
		text-align: center;
		display: block;
		visibility: visible;
	}
	.detail {
		
	}
	.detail img {
		width: 100%;
	}
</style>