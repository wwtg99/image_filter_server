<template>
	<div class="imageflow">
		<img v-for="(image, index) in images" :src="image.src" :data-info="image.title" :style="imageStyles[index]" ref="img" @click="handleClick(index)">
		<div class="title">{{ title }}</div>
	</div>
</template>

<script>
export default {
	name: 'imageflow',
	data () {
		return {
			imageStyles: [],
			title: '',
			curIndex: 0
		}
	},
	computed: {
		imageNum() {
			return this.images.length;
		},
		width() {
			return this.$el.width;
		}
	},
	props: {
		images: {
			type: Array,
			required: true
		},
		index: {
			type: Number,
			default: -1
		},
		flat: {
			type: Boolean,
			default: false
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
			this.imageStyles = new Array(this.imageNum);
			if (this.index < 0) {
				this.curIndex = parseInt(this.imageNum / 2);
			} else {
				this.curIndex = this.index;
			}
		},
		display() {
			this.setTransform3D(this.$el, 0, 600, 0);
			this.showIndex();
		},
	    setTransform3D(el, degree, perspective, z) {
			degree = Math.max(Math.min(degree, 90), -90);
			z -= 5;
			el.style['-webkit-perspective'] = el.style['perspective'] = el.style['-moz-perspective'] = perspective + 'px'
			el.style['-webkit-transform'] = el.style['transform'] = 'rotateY(' + degree + 'deg) translateZ(' + z + 'px)'
	    },
	    showIndex() {
	    	let scrollLeft = this.$el.scrollLeft;
			let mleft = (this.width - this.imageWidth) * 0.5 - this.imageSpace * (this.curIndex + 1) - this.imageWidth * 0.5;
			//left of current images
			for (var i = 0; i <= this.curIndex; i++) {
				this.imageStyles[i] = {
					left: (scrollLeft + i * this.imageSpace + this.imageSpace) + 'px',
					marginLeft: mleft + 'px',
					'-webkit-filter': 'brightness(0.65)',
					zIndex: i + 1
				};
				this.setTransform3D(this.$refs.img[i], this.flat ? 0 : ((this.curIndex - i) * 10 + 45), 300, this.flat ? -(this.curIndex - i) * 10 : (-(this.curIndex - i) * 30 - 20));
			}
			//current images
			this.imageStyles[this.curIndex]['-webkit-filter'] = 'none';
			this.imageStyles[this.curIndex].marginLeft = mleft + 'px';
			this.imageStyles[this.curIndex].marginLeft = (mleft + this.imageWidth * 0.5) + 'px';
			this.imageStyles[this.curIndex].zIndex = this.imageNum;
			this.title = this.images[this.curIndex].title;
			this.setTransform3D(this.$refs.img[this.curIndex], 0, 0, 5);
			//right of current images
			for (var i = this.curIndex + 1; i < this.imageNum; i++) {
				this.imageStyles[i] = {
					left: (scrollLeft + i * this.imageSpace + this.imageSpace) + 'px',
					marginLeft: (mleft + this.imageWidth) + 'px',
					'-webkit-filter': 'brightness(0.7)',
					zIndex: this.imageNum - i
				};
				this.setTransform3D(this.$refs.img[i], this.flat ? 0 : ((this.curIndex - i) * 10 - 45), 300, this.flat ? (this.curIndex - i) * 10 : ((this.curIndex - i) * 30 - 20));
			}
		},
		handleClick (index) {
			this.curIndex = index;
			this.showIndex();
		},
		handleScroll () {
			let p = 1.0 * this.$el.scrollLeft / this.width;
			let index = Math.min(Math.floor(p * this.imageNum), this.imageNum);
			this.curIndex = index;
			this.showIndex();
	    },


    displayIndex(imgSize, spacing, left, imgs, index, flat, width, titleBox) {
      var mLeft = (width - imgSize) * 0.5 - spacing * (index + 1) - imgSize * 0.5
      for (var i = 0; i <= index; ++i) {
        imgs[i].style.left = (left + i * spacing + spacing) + 'px'
        imgs[i].style.marginLeft = mLeft + 'px'
        imgs[i].style['-webkit-filter'] = 'brightness(0.65)'
        imgs[i].style.zIndex = i + 1
        this.setTransform3D(imgs[i], flat ? 0 : ((index - i) * 10 + 45), 300, flat ? -(index - i) * 10 : (-(index - i) * 30 - 20))
      }
      imgs[index].style['-webkit-filter'] = 'none'
      imgs[index].style.marginLeft = (mLeft + imgSize * 0.5) + 'px'
      imgs[index].style.zIndex = imgs.length
      titleBox.style.visibility = 'hidden'
      if (imgs[index].dataset.info) {
        titleBox.style.visibility = 'visible'
        var info = imgs[index].dataset.info
        titleBox.innerHTML = info
        titleBox.style.left = (left + index * spacing + spacing + 10) + 'px'
        titleBox.style.marginLeft = (mLeft + imgSize * 0.5) + 'px'
      }
      this.setTransform3D(imgs[index], 0, 0, 5)
      for (var j = index + 1; j < imgs.length; ++j) {
        imgs[j].style.left = (left + j * spacing + spacing) + 'px'
        imgs[j].style.marginLeft = (mLeft + imgSize) + 'px'
        imgs[j].style['-webkit-filter'] = 'brightness(0.7)'
        imgs[j].style.zIndex = imgs.length - j
        this.setTransform3D(imgs[j], flat ? 0 : ((index - j) * 10 - 45), 300, flat ? (index - j) * 10 : ((index - j) * 30 - 20))
      }
    },
    coverflowScroll(imgSize, spacing, c, imgs, flat, titleBox) {
      var width = parseInt(c.style.width)
      var p = 1.0 * c.scrollLeft / width
      var index = Math.min(Math.floor(p * imgs.length), imgs.length - 1)
      var left = c.scrollLeft
      c.dataset.index = index
      this.displayIndex(imgSize, spacing, left, imgs, index, flat, width, titleBox)
    },
    initCoverFlow(c) {
      var imgSize = parseInt(c.dataset.size) || 64
      var spacing = parseInt(c.dataset.spacing) || 10
      var shadow = (c.dataset.shadow === 'true') || false
      var imgShadow = !((c.dataset.imgshadow === 'false') || false)
      var bgColor = c.dataset.bgcolor || 'transparent'
      var flat = (c.dataset.flat === 'true') || false
      var width = c.dataset.width
      var index = c.dataset.index
      var imgHeight = 0
      var imgs = []
      var placeholding
      var browserPrefix = ''
      if (navigator.userAgent.indexOf('Firefox') !== -1) {
        browserPrefix = '-moz-'
      } else if (navigator.userAgent.indexOf('Chrome') !== -1) {
        browserPrefix = '-webkit-'
      } else if (navigator.userAgent.indexOf('Safari') !== -1) {
        browserPrefix = '-webkit-'
      }
      for (var i = 0; i < c.childNodes.length; ++i) {
        if (c.childNodes[i].tagName) {
          imgs.push(c.childNodes[i])
        }
      }
      for (var j = 0; j < imgs.length; ++j) {
        imgs[j].style.position = 'absolute'
        imgs[j].style.width = imgSize + 'px'
        imgs[j].style.height = 'auto'
        imgs[j].style.bottom = '60px'
        if (!shadow && imgShadow) {
          imgs[j].style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.3)'
        }
        imgs[j].style['transition'] = browserPrefix + 'transform .4s ease, margin-left .4s ease, -webkit-filter .4s ease'
        imgHeight = Math.max(imgHeight, imgs[j].height);
      }
      console.log(imgHeight);
      c.style.overflowX = 'hidden'
      c.style.backgroundColor = bgColor
      var titleBox = document.createElement('SPAN')
      if (!shadow) {
        titleBox.className = 'coverflow-title-box'
        titleBox.style.position = 'absolute'
        titleBox.style.width = (imgSize - 20) + 'px'
        titleBox.style.height = '20px'
        titleBox.style.lineHeight = '20px'
        titleBox.style.fontSize = '14px'
        titleBox.style.padding = '0 3px'
        titleBox.style.color = '#222'
        titleBox.style.background = '#ddd'
        titleBox.style.borderRadius = '10px'
        titleBox.style.fontWeight = 'normal'
        titleBox.style.fontFamily = '"Helvetica Neue", Helvetica, Arial, sans-serif'
        titleBox.style.bottom = '28px'
        titleBox.style.textAlign = 'center'
        titleBox.style.display = 'block'
        c.appendChild(titleBox)
      }
      this.setTransform3D(c, 0, 600, 0)
      placeholding = document.createElement('DIV')
      placeholding.style.width = (width ? width * 2 : (imgSize + (imgs.length + 1) * spacing) * 2) + 'px'
      placeholding.style.height = '1px'
      c.appendChild(placeholding)
      if (width) {
        c.style.width = width + 'px'
      } else {
        c.style.width = (imgSize + (imgs.length + 1) * spacing) + 'px'
      }
      if (shadow) {
        c.style.height = (imgHeight * 2 + 80) + 'px'
        c.style['-webkit-perspective-origin'] = c.style['perspective-origin'] = c.style['-moz-perspective-origin'] = '50% 25%'
        for (var k = 0; k < imgs.length; ++k) {
          imgs[k].style.bottom = (20 + imgHeight) + 'px'
          imgs[k].style['-webkit-box-reflect'] = 'below 0 -webkit-gradient(linear, 30% 20%, 30% 100%, from(transparent), color-stop(0.3, transparent), to(rgba(0, 0, 0, 0.8)))'
        }
      } else {
        c.style.height = (imgHeight + 80) + 'px'
      }
      c.style.position = 'relative'
      c.dataset.index = index ? parseInt(index) : 0
      c.onscroll = function () {
        this.coverflowScroll(imgSize, spacing, c, imgs, flat, titleBox)
      }
      var that = this;
      for (var l = 0; l < imgs.length; ++l) {
        imgs[l].onclick = function () {
          that.displayIndex(imgSize, spacing, c.scrollLeft, imgs, imgs.indexOf(this), flat, parseInt(c.style.width), titleBox)
        }
      }
      this.displayIndex(imgSize, spacing, c.scrollLeft, imgs, +c.dataset.index, flat, parseInt(c.style.width), titleBox)
    }
  },
  created() {
		this.init();
  },
  mounted() {
    // this.initCoverFlow(this.$el);
    this.display();
  }
}
</script>

<style scoped>
	.imageflow {
		margin: 0 auto;
		cursor: pointer;
		overflow-x: hidden;
		background-color: #fff;
		width: 100%;
		min-height: 300px;
	}
	.imageflow img {
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
		width: 240px;
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
		left: 260px;
		margin-left: 120px;
	}
</style>