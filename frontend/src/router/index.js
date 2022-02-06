import Vue from 'vue'
import VueRouter from 'vue-router'
import {createRouterLayout} from 'vue-router-layout'

Vue.use(VueRouter)
//
const RouterLayout = createRouterLayout(layout => {
	return import('@/layouts/' + layout + '.vue')
})

const routes = [
	{
		path: '/',
		name: 'index',
		component: () => import( '@/pages/Index.vue')
	},
	{
		path: '/admin',
		name: 'admin',
		component: () => import( '@/pages/admin/index.vue'),
		children: [
			{
				path: 'list',
				name: 'adminList',
				component: () => import( '@/pages/admin/list.vue')
			},
			{
				path: 'edit/:id',
				name: 'adminEdit',
				component: () => import( '@/pages/admin/edit.vue')
			},			{
				path: 'create',
				name: 'adminCreate',
				component: () => import( '@/pages/admin/edit.vue')
			},
			{
				path: 'permission',
				name: 'permission',
				component: () => import( '@/pages/permission/list.vue')
			},
			{
				path: 'permission/edit/:id',
				name: 'permissionEdit',
				component: () => import( '@/pages/permission/edit.vue')
			},			{
				path: 'permission/create',
				name: 'permissionCreate',
				component: () => import( '@/pages/permission/edit.vue')
			},
		]
	},
	{
		path: '/user',
		name: 'user',
		component: () => import( '@/pages/user/index.vue'),
		children: [
			{
				path: 'list',
				name: 'userList',
				component: () => import( '@/pages/user/list.vue')
			},
			{
				path: 'edit/:id',
				name: 'userEdit',
				component: () => import( '@/pages/user/edit.vue')
			},
			{
				path: 'create',
				name: 'userCreate',
				component: () => import( '@/pages/user/edit.vue')
			}
		]
	},
    {
        path: '/user/group',
        name: 'userGroup',
        component: () => import( '@/pages/user/group/index.vue'),
        children: [
            {
                path: 'list',
                name: 'userGroup',
                component: () => import( '@/pages/user/group/list.vue')
            },
            {
                path: 'edit/:id',
                name: 'userGroupEdit',
                component: () => import( '@/pages/user/group/edit.vue'),
                hide: true,
            },
            {
                path: 'create',
                name: 'userGroupCreate',
                component: () => import( '@/pages/user/group/edit.vue'),
                hide: true,
            },
        ]
    },
	{
		path: '/product',
		name: 'product',
		component: () => import( '@/pages/product/index.vue'),
		children: [
			{
				path: 'list',
				name: 'productList',
				component: () => import( '@/pages/product/list.vue')
			},
			{
				path: 'edit/:id',
				name: 'productEdit',
				component: () => import( '@/pages/product/edit.vue'),
				hide: true,
			},
			{
				path: 'create',
				name: 'productCreate',
				component: () => import( '@/pages/product/edit.vue'),
				hide: true,
			}
		]
	},
    {
        path: '/product/category',
        name: 'productCategory',
        component: () => import( '@/pages/product/category/index.vue'),
        children: [
            {
                path: 'list',
                name: 'productCategory',
                component: () => import( '@/pages/product/category/list.vue'),
                hide: true,
            },
            {
                path: 'create',
                name: 'productCategoryCreate',
                component: () => import( '@/pages/product/category/edit.vue'),
                hide: true,
            },
            {
                path: 'edit/:id',
                name: 'productCategoryEdit',
                component: () => import( '@/pages/product/category/edit.vue'),
                hide: true,
            }
        ]
    },
	{
		path: '/news',
		name: 'news',
		component: () => import( '@/pages/news/index.vue'),
		children: [
			{
				path: 'list',
				name: 'newsList',
				component: () => import( '@/pages/news/list.vue')
			},
			{
				path: 'edit/:id',
				name: 'newsEdit',
				component: () => import( '@/pages/news/edit.vue'),
				hide: true,
			},
			{
				path: 'create',
				name: 'newsCreate',
				component: () => import( '@/pages/news/edit.vue'),
				hide: true,
			},
		]
	},
    //newsCategory
    {
        path: '/news/category',
        name: 'newsCategory',
        component: () => import( '@/pages/news/category/index.vue'),
        children: [
            {
                path: 'list',
                name: 'newsCategory',
                component: () => import( '@/pages/news/category/list.vue'),
            },
            {
                path: 'category/edit/:id',
                name: 'newsCategoryEdit',
                component: () => import( '@/pages/news/category/edit.vue'),
                hide: true,
            },
            {
                path: 'category/create',
                name: 'newsCategoryCreate',
                component: () => import( '@/pages/news/category/edit.vue'),
                hide: true,
            }
        ]
    },
	{
		path: '/media',
		name: 'media',
		component: () => import( '@/pages/media/index.vue'),
		children: [
			{
				path: 'image',
				name: 'image',
				component: () => import( '@/pages/media/image.vue')
			}
		]
	},
	{
		path: '/report',
		name: 'report',
		component: () => import( '@/pages/report/index.vue'),
		children: [
			{
				path: 'saleReport',
				name: 'saleReport',
				component: () => import( '@/pages/report/saleReport.vue')
			},
			{
				path: 'orderReport',
				name: 'orderReport',
				component: () => import( '@/pages/report/orderReport.vue')
			},
		]
	},
	{
		path: '/system',
		name: 'system',
		component: () => import( '@/pages/system/index.vue'),
		children: [
			{
				path: 'setting',
				name: 'setting',
				component: () => import( '@/pages/system/setting.vue')
			}
		]
	},
	{
		path: '/logRecord',
		name: 'logRecord',
		component: () => import( '@/pages/logRecord/index.vue'),
		children: [
			{
				path: 'list',
				name: 'logRecordList',
				component: () => import( '@/pages/logRecord/list.vue')
			}
		]
	},
	{
		path: '/login',
		name: 'login',
		component: () => import( '@/pages/login.vue')
	},
	{
		path: '/auth',
		name: 'auth',
		children: [
			{
				path: 'google',
				name: 'google',
			},
		]
	}
]

const router = new VueRouter({
	mode: 'history',
	routes: [
		{
			path: '/',
			component: RouterLayout,
			children: routes
		}
	]
})

router.beforeEach(async (to, from, next) => {
	let checkSuccess = true
	if (to.path === '/auth/google') {
		await router.app.$options.store.dispatch('checkAuthGoogle', to.query).then(() => {
			checkSuccess = true
			if (to.name === 'google') router.push('/')
		}).catch(() => {
			checkSuccess = false
			next({
				path: '/login',
			})
		})
	} else {
		await router.app.$options.store.dispatch('checkAuth').then(() => {
			checkSuccess = true
			if (to.name === 'login') router.push('/')
		}).catch(() => {
			if (to.name !== 'login') {
				checkSuccess = false
				next({
					path: '/login',
					query: {
						returnUrl: to.fullPath,
					}
				})
			}
		})
	}

	if (checkSuccess) {
		next()
	}
});

export default router
