<template>
	<nav class="layout-footer footer" :class="getLayoutFooterBg()">
		<div class="container-fluid container-p-x pb-3">
			<div class="footer-link">
				Copyright &copy; 2020
			</div> 
			<a href="https://www.4visionmedia.com/id" target="_blank" class="footer-link pt-3"><b>4 Vision Media</b></a>
		</div>
	</nav>
</template>

<script>
export default {
	name: 'app-layout-footer',

	methods: {
		getLayoutFooterBg () {
			return `bg-${this.layoutFooterBg}`
		}
	}
}
</script>
