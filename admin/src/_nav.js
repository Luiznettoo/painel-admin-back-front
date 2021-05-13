export default {
	items: [
		{
			name: 'Home',
			url: '/admin/dashboard',
			icon: 'fas fa-home',
		},
		{
			name: 'Usuários',
			url: '/admin/users',
			icon: 'fas fa-users',
		},
		{
			name: 'Arquivos',
			url: '/admin/files',
			icon: 'fas fa-folder',
		},
    {
      name: 'Paginas',
      icon: 'far fa-file',
      url:  '#',
      children: [
        {
          name: 'Home',
          url: '/admin/home',
        },
        {
          name: 'Serviços',
          url: '/admin/servicos',
        },
        {
          name: 'Contato',
          url: '/admin/contato',
        },
      ],
    },
    // {
    //   name: 'Banners',
    //   url: '/admin/textsBanner',
    //   icon: 'fas fa-users'
    // },
		// {
		// 	name: 'Produtos',
		// 	icon: 'far fa-box',
		// 	url:  '#',
		// 	children: [
		// 		{
		// 			name: 'Ambiente',
		// 			icon: 'fas fa-box-open',
		// 			url: '/admin/ambiences',
		// 		},
		// 		{
		// 			name: 'Categoria',
		// 			icon: 'fas fa-boxes',
		// 			url: '/admin/categorias',
		// 		},
		// 		{
		// 			name: 'Produtos',
		// 			icon: 'far fa-box',
		// 			url: '/admin/products',
		// 		}
		// 	]
		// },
    {
      name: 'Serviços',
      url: '/admin/services',
      icon: 'fas fa-comments'
    },
    {
      name: 'Depoimentos',
      url: '/admin/testimonials',
      icon: 'fas fa-comments'
    },
    {
      name: 'Equipe',
      url: '/admin/teams',
      icon: 'fas fa-users'
    },
    {
      name: 'Planos',
      url: '/admin/plans',
      icon: 'fas fa-users'
    },
    {
      name: 'Convênios',
      url: '/admin/covenant',
      icon: 'fas fa-users'
    },
    {
			name: "Instagram",
			icon: 'fab fa-instagram',
			url: '/admin/instagram',
		},
		// {
		// 	name: 'Acabamentos',
		// 	icon: 'far fa-calendar-check',
		// 	url:  '#',
		// 	children: [
		// 		{
		// 			name: 'Categoria',
		// 			url: '/admin/categoriasa',
		// 		},
		// 		{
		// 			name: 'Acabamentos',
		// 			url: '/admin/acabamentos',
		// 		}
		// 	]
		// },
		// {
		// 	name: 'Opcionais',
		// 	icon: 'fas fa-clipboard-list',
		// 	url: '#',
		// 	children: [
		// 		{
		// 			name: 'Categoria',
		// 			url: '/admin/groups',
		// 		},
		// 		{
		// 			name: 'Opções',
		// 			url: '/admin/options',
		// 		},
		// 	]
		// },
		{
			name: 'Blog',
			icon: 'fas fa-comment',
			url:  '#',
			children: [
				{
					name: 'Categoria',
					icon: 'fas fa-folder',
					url: '/admin/categoriasb',
				},
				{
					name: 'Publicação',
					icon: 'far fa-file-alt',
					url: '/admin/blogs',
				}
			]
		},
    {
      name: 'Seos',
      url: '/admin/seos',
      icon: 'fas fa-users'
    },
    {
      name: 'Geral',
      url: '/admin/common',
      icon: 'far fa-info',
    },
		{
			name: 'Orçamentos',
			url: '/admin/orders',
			icon: 'far fa-newspaper',
		},
		{
			name: 'Newsletter',
			url: '/admin/newsletter',
			icon: 'fas fa-inbox',
		},
	]
};
