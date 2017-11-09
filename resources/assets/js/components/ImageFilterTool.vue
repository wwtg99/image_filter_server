<template>
	<div class="image_filter">
		<el-row type="flex">
			<el-col :span="12" :offset="6">
				<h2>立即试用</h2>
			</el-col>
		</el-row>
		<!-- filter select -->
		<el-row type="flex" class="filter_row">
			<el-col :span="12" :offset="6">
				<el-select v-model="filter" filterable placeholder="滤镜" @change="handleFilterChange">
					<el-option v-for="(item, f) in allFilters" :key="f" :label="item.label" :value="f">
					</el-option>
				</el-select>
			</el-col>
		</el-row>
		<!-- filter param -->
		<el-row type="flex" class="param_row" v-if="showParam">
			<el-col :span="8" :offset="8">
				<el-form ref="form" label-width="80px">
					<template v-for="(val, key) in currParams">
						<el-form-item v-if="key == 'size'" :label="val.label" :key="key">
							<el-select v-model.number="size">
								<el-option v-for="i in val.value" :label="i" :value="i" :key="i"></el-option>
							</el-select>
						</el-form-item>
						<el-form-item v-else-if="key == 'radius'" :label="val.label" :key="key">
							<el-input-number v-model.number="radius" :max="val.max" :min="val.min"></el-input-number>
						</el-form-item>
						<el-form-item v-else-if="key == 'percent'" :label="val.label" :key="key">
							<el-input-number v-model.number="percent" :max="val.max" :min="val.min"></el-input-number>
						</el-form-item>
						<el-form-item v-else-if="key == 'threshold'" :label="val.label" :key="key">
							<el-input-number v-model.number="threshold" :max="val.max" :min="val.min"></el-input-number>
						</el-form-item>
					</template>
				</el-form>
			</el-col>
		</el-row>
		<!-- file upload -->
		<el-row type="flex">
			<el-col :span="12" :offset="6">
				<el-upload class="upload" ref="upload" name="input" 
					action="/api/image/filter"
					:show-file-list="false"
					:data="uploadParam"
					:before-upload="handleBeforeUpload"
					:on-success="handleSuccess"
					:file-list="fileList">
					<el-button size="small" type="primary">点击上传</el-button>
					<div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过 2 M!</div>
				</el-upload>
			</el-col>
		</el-row>
		<!-- loading -->
		<el-row type="flex" v-show="loading">
			<el-col :span="24">
				<div>
					<img src="../../images/loading.svg">
				</div>
			</el-col>
		</el-row>
		<!-- results -->
		<el-row type="flex" class="result_row" v-if="showResult">
			<el-col :span="24">
				<image-flow v-if="showResult" :images="images" :width="this.$el.clientWidth"></image-flow>
			</el-col>
		</el-row>
	</div>
</template>

<script>
    export default {
    	name: 'imagefiltertool',
    	data() {
			return {
				filter: 'all',
				fileList: [],
				currParams: {},  //current filter param
				radius: 2,
				size: 3,
				percent: 150,
				threshold: 3,
				loading: false,
				showParam: false,
				showResult: false,
				images: [],
				allFilters: {
					'all': {
						'label': '所有', 
						'descr': ''
					},
					'blur': {
						'label': 'Blur 模糊', 
						'descr': 'blur'
					},
					'contour': {
						'label': 'Contour 描边', 
						'descr': 'contour'
					},
					'edge_curve': {
						'label': 'Edge Curve 边缘雕刻', 
						'descr': 'edge_curve'
					},
					'edge_enhance': {
						'label': 'Edge Enhance 边缘强化', 
						'descr': 'edge_enhance'
					},
					'emboss': {
						'label': 'Emboss 浮雕', 
						'descr': 'emboss'
					},
					'emboss_45d': {
						'label': 'Emboss 45d 45 度浮雕', 
						'descr': 'emboss_45d'
					},
					'emboss_asym': {
						'label': 'Emboss Asymmetric 非对称浮雕', 
						'descr': 'emboss_asym'
					},
					'gaussian_blur': {
						'label': 'Gaussian Blur 高斯模糊', 
						'descr': 'gaussian_blur', 
						'param': {
							'radius': {'label': '半径', 'value': 2, 'type': 'number', 'max': 100, 'min': 1}
						}
					},
					'grey': {
						'label': 'Grey 灰度', 
						'descr': 'grey'
					},
					'hand_drawn': {
						'label': 'Hand Drawn 手绘', 
						'descr': 'hand_drawn'
					},
					'min': {
						'label': 'Min Filter 最小值滤镜', 
						'descr': 'min', 
						'param': {
							'size': {'label': '大小', 'value': [3, 5], 'type': 'select'}
						}
					},
					'median': {
						'label': 'Median Filter 中值滤镜', 
						'descr': 'median', 
						'param': {
							'size': {'label': '大小', 'value': [3, 5], 'type': 'select'}
						}
					},
					'max': {
						'label': 'Max Filter 最大值滤镜', 
						'descr': 'max', 
						'param': {
							'size': {'label': '大小', 'value': [3, 5], 'type': 'select'}
						}
					},
					'mode': {
						'label': 'Mode Filter 最常值滤镜', 
						'descr': 'mode', 
						'param': {
							'size': {'label': '大小', 'value': [3, 5], 'type': 'select'}}
						},
					'rgb': {
						'label': 'RGB', 
						'descr': 'rgb'
					},
					'rgba': {
						'label': 'RGBA', 
						'descr': 'rgba'
					},
					'sharpen': {
						'label': 'Sharpen 锐化', 
						'descr': 'sharpen'
					},
					'sharp_center': {
						'label': 'Sharp Edge 中心锐化', 
						'descr': 'sharp_center'
					},
					'sharp_edge': {
						'label': 'Sharp Edge 边缘锐化', 
						'descr': 'sharp_edge'
					},
					'smooth': {
						'label': 'Smooth 平滑', 
						'descr': 'smooth'
					},
					'unsharp_mask': {
						'label': 'Unsharp Mask 锐化遮罩', 
						'descr': 'unsharp_mask', 
						'param': {
							'radius': {'label': '半径', 'value': 2, 'type': 'number', 'max': 100, 'min': 1}, 
							'percent': {'label': '强度', 'value': 150, 'type': 'number', 'placeholder': '强度百分比', 'max': 300, 'min': 1}, 
							'threshold': {'label': '阈值', 'value': 3, 'type': 'number', 'placeholder': '最小亮度阈值', 'max': 100, 'min': 1}
						}
					},
				}
			};
	    },
	    computed: {
	    	uploadParam() {
				let p = JSON.stringify({radius: this.radius, size: this.size, percent: this.percent, threshold: this.threshold});
				return {filter: this.filter, param: p};
	    	},
	    },
	    methods: {
	    	handleFilterChange(filter) {
	    		if (filter in this.allFilters && 'param' in this.allFilters[filter]) {
	    			this.currParams = this.allFilters[filter].param;
	    			this.showParam = true;
	    		} else {
	    			this.showParam = false;
	    		}
	    	},
			handleBeforeUpload(file) {
				if (['image/jpeg', 'image/png'].indexOf(file.type) < 0) {
					this.$message.error('上传图片只能是 JPG/PNG 格式！');
					return false;
				}
				if (file.size / 1024 / 1024 > 2) {
					this.$message.error('上传图片大小不能超过 2 M！');
					return false;
				}
				this.loading = true;
				this.showResult = false;
			},
			handleSuccess(response, file, fileList) {
				console.log(response);  //TODO
				if ('job_id' in response) {
					this.getResult(response.job_id, true);
				}
			},
			getResult(jobId, thumbnail) {
				axios.get('/api/image/get_result',{
					params:{
						job_id: jobId,
						thumbnail: thumbnail
					}
				})
				.then(function(response){
					if ('images' in response.data) {
						this.images = [];
						for (var i in response.data.images) {
							this.images.push({'title': this.getFilterTitle(i), 'src': response.data.images[i]});
						}
					}
					this.showResult = true;
					this.loading = false;
				}.bind(this))
				.catch(function(err){
					console.log(err);
				});
			},
			getFilterTitle(filter) {
				switch(filter) {
					case 'blur': return 'Blur 模糊';
					case 'contour': return 'Contour 描边';
					case 'edge_curve': return 'Edge Curve 边缘雕刻';
					case 'edge_enhance': return 'Edge Enhance 边缘强化';
					case 'emboss': return 'Emboss 浮雕';
					case 'emboss_45d': return 'Emboss 45d 45 度浮雕';
					case 'emboss_asym': return 'Emboss Asymmetric 非对称浮雕';
					case 'gaussian_blur': return 'Gaussian Blur 高斯模糊';
					case 'grey': return 'Grey Scale 灰度';
					case 'hand_drawn': return 'Hand Drawn 手绘';
					case 'max': return 'Max Filter 最大值滤镜';
					case 'median': return 'Median Filter 中值滤镜';
					case 'min': return 'Min Filter 最小值滤镜';
					case 'mode': return 'Mode Filter 最常值滤镜';
					case 'sharpen': return 'Sharpen 锐化';
					case 'sharp_center': return 'Sharp Center 中心锐化';
					case 'sharp_edge': return 'Sharp Edge 边缘锐化';
					case 'smooth': return 'Smooth 平滑';
					case 'unsharp_mask': return 'Unsharp Mask 锐化遮罩';
				}
				return filter;
			}
	    }
    }
</script>

<style scoped>
	.image_filter {
		text-align: center;
	}
	.filter_row {
		padding: 10px 0 20px 0;
	}
	.result_row {
		margin: 30px 0;
		min-height: 300px;
	}
</style>
