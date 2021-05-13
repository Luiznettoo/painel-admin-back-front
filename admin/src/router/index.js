import Vue    from 'vue';
import Router from 'vue-router';

import Users        from '@/views/users/Users.vue';
import UsersForm    from '@/views/users/UsersForm.vue';

import Ambiente     from "@/views/product/Ambiente";
import AmbienteForm from "@/views/product/AmbienteForm";

import Categoria     from "@/views/product/Categoria";
import CategoriaForm from "@/views/product/CategoriaForm";

import Product     from "@/views/product/Product";
import ProductForm from "@/views/product/ProductForm";

import Blog     from "@/views/blog/Blog";
import BlogForm from "@/views/blog/BlogForm";

import Categoriab     from "@/views/blog/Categoria";
import CategoriabForm from "@/views/blog/CategoriaForm";

import Option     from "@/views/option/Option";
import OptionForm from "@/views/option/OptionForm";

import CategoriaOption     from "@/views/option/Categoria";
import CategoriaOptionForm from "@/views/option/CategoriaForm";

import Home     from "@/views/pages/Home";
import Servicos from "@/views/pages/Servicos";
import Contato  from "@/views/pages/Contato";

import Order     from "@/views/order/Order";
import OrderView from "@/views/order/OrderView";

import Acabamento     from "@/views/acabamento/Acabamento";
import AcabamentoForm from "@/views/acabamento/AcabamentoForm";

import Categoriaa     from "@/views/acabamento/Categoria";
import CategoriaaForm from "@/views/acabamento/CategoriaForm";

import Services     from "@/views/services/Services";
import ServicesForm from "@/views/services/ServicesForm";

import Covenants     from "@/views/covenants/Covenants";
import CovenantsForm from "@/views/covenants/CovenantsForm";


import Testimonials     from "@/views/tetimonials/Testimonials";
import TestimonialsForm from "@/views/tetimonials/TestimonialsForm";

import TextBanner     from "@/views/textBanner/TextBanner";
import TextBannerForm from "@/views/textBanner/TextBannerForm";

import Seos     from "@/views/seo/Seos";
import SeosForm from "@/views/seo/SeosForm";

import Plans from "@/views/plans/Plans";
import PlansForm from "@/views/plans/PlansForm";

import Teams     from "@/views/teams/Teams";
import TeamsForm from "@/views/teams/TeamsForm";

import Instagram     from "@/views/instagram/Instagram";
import InstagramForm from "@/views/instagram/InstagramForm";

import Newsletter     from "@/views/newsletter/Newsletter";
import NewsletterView from "@/views/newsletter/Newsletter";

import PageProduto from "@/views/pages/Produto";
import Common      from "../views/common/Common";

/* Inicio da alteração */

/* Fim da alteração */

// Containers
const DefaultContainer = () => import('@/containers/DefaultContainer');

// Views - Pages
const Login = () => import('@/views/Login');
const Files = () => import('@/views/Ftp/Files');

Vue.use(Router);

const router = new Router({
    mode           : 'history',
    linkActiveClass: 'open active',
    scrollBehavior : () => ({y: 0}),
    routes         : [
        {
            path     : '/admin/',
            redirect : '/admin/dashboard',
            name     : 'Main',
            component: DefaultContainer,
            children : [
                {
                    path     : '/admin/dashboard',
                    name     : 'Dashboard',
                    component: {
                        template: '<span></span>'
                    }
                },
                {
                    path     : '/admin/users',
                    name     : 'Users',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Listar Usuário',
                            component: Users
                        },
                        {
                            path     : '/admin/user/:target?',
                            name     : 'Usuário',
                            component: UsersForm
                        }
                    ]
                },
                {
                    path     : '/admin/files',
                    name     : 'Arquivos',
                    component: Files,
                },
                /*
                ---------------------------------------------------------
                aqui começa a alteração
                ---------------------------------------------------------
                */
                {
                    path     : '/admin/ambiences',
                    name     : 'Ambiente',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Listar Ambiente',
                            component: Ambiente,
                        },
                        {
                            path     : '/admin/ambience/:target?',
                            name     : 'Cadastrar Ambiente',
                            component: AmbienteForm,
                        }
                    ]
                },
                {
                    path     : '/admin/categorias',
                    name     : 'Categoria',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Listar Categorias de Produto',
                            component: Categoria
                        },
                        {
                            path     : '/admin/categoria/:target?',
                            name     : 'Cadastrar Categorias de Produto',
                            component: CategoriaForm
                        }
                    ]
                },
                {
                    path     : '/admin/products',
                    name     : 'Produto',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Listar Produto',
                            component: Product
                        },
                        {
                            path     : '/admin/product/:target?',
                            name     : 'Cadastrar Produto',
                            component: ProductForm
                        }
                    ]
                },
                {
                  path     : '/admin/testimonials',
                  name     : 'Depoimentos',
                  component: {
                    render(_) {
                      return _('router-view');
                    }
                  },
                  children : [
                    {
                      path     : '',
                      name     : 'Listar Depoimentos',
                      component: Testimonials
                    },
                    {
                      path     : '/admin/testimonial/:target?',
                      name     : 'Cadastrar Depoimento',
                      component: TestimonialsForm
                    }
                  ]
                },
                {
                  path     : '/admin/services',
                  name     : 'Serviços',
                  component: {
                    render(_) {
                      return _('router-view');
                    }
                  },
                  children : [
                    {
                      path     : '',
                      name     : 'Listar Serviços',
                      component: Services
                    },
                    {
                      path     : '/admin/service/:target?',
                      name     : 'Cadastrar Serviços',
                      component: ServicesForm
                    }
                  ]
                },
                {
                  path     : '/admin/covenant',
                  name     : 'Convênios',
                  component: {
                    render(_) {
                      return _('router-view');
                    }
                  },
                  children : [
                    {
                      path     : '',
                      name     : 'Listar Convênios',
                      component: Covenants
                    },
                    {
                      path     : '/admin/covenants/:target?',
                      name     : 'Cadastrar Convênios',
                      component: CovenantsForm
                    }
                  ]
                },
                {
                  path     : '/admin/teams',
                  name     : 'Equipe',
                  component: {
                    render(_) {
                      return _('router-view');
                    }
                  },
                  children : [
                    {
                      path     : '',
                      name     : 'Listar Equipe',
                      component: Teams
                    },
                    {
                      path     : '/admin/team/:target?',
                      name     : 'Cadastrar Equipe',
                      component: TeamsForm
                    }
                  ]
                },
                {
                  path     : '/admin/textsBanner',
                  name     : 'Textos do Banner',
                  component: {
                    render(_) {
                      return _('router-view');
                    }
                  },
                  children : [
                    {
                      path     : '',
                      name     : 'Listar Textos',
                      component: TextBanner
                    },
                    {
                      path     : '/admin/textBanner/:target?',
                      name     : 'Cadastrar Banners',
                      component: TextBannerForm
                    }
                  ]
                },
                {
                  path     : '/admin/seos',
                  name     : 'Seos das Páginas',
                  component: {
                    render(_) {
                      return _('router-view');
                    }
                  },
                  children : [
                    {
                      path     : '',
                      name     : 'Listar Seos',
                      component: Seos
                    },
                    {
                      path     : '/admin/seo/:target?',
                      name     : 'Cadastrar Seos',
                      component: SeosForm
                    }
                  ]
                },
              {
                path     : '/admin/plans',
                name     : 'Páginas de Planos  ',
                component: {
                  render(_) {
                    return _('router-view');
                  }
                },
                children : [
                  {
                    path     : '',
                    name     : 'Listar Planos',
                    component: Plans
                  },
                  {
                    path     : '/admin/plan/:target?',
                    name     : 'Cadastrar Planos',
                    component: PlansForm
                  }
                ]
              },
                {
                    path     : '/admin/instagram',
                    name     : 'Instagram',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Listar Instagram',
                            component: Instagram
                        },
                        {
                            path     : '/admin/instagra/:target?',
                            name     : 'Cadastrar Instagram',
                            component: InstagramForm
                        }
                    ]
                },
                {
                    path     : '/admin/newsletter',
                    name     : 'Newsletter',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Listar Newsletter',
                            component: Newsletter
                        },
                        {
                            path     : '/admin/newslette/:target?',
                            name     : 'Visualizar Newsletter',
                            component: NewsletterView
                        }
                    ]
                },
                {
                    path     : '/admin/categoriasb',
                    name     : 'Categoria Blog',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Listar Categorias de Blog',
                            component: Categoriab
                        },
                        {
                            path     : '/admin/categoriab/:target?',
                            name     : 'Cadastrar Categorias de Blog',
                            component: CategoriabForm
                        }
                    ]
                },
                {
                    path     : '/admin/blogs',
                    name     : 'Blog',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Listar Publicações',
                            component: Blog,
                        },
                        {
                            path     : '/admin/blog/:target?',
                            name     : 'Cadastrar Publicação',
                            component: BlogForm
                        }
                    ]
                },
                {
                    name     : 'Opcionais',
                    path     : '/admin/options',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Listar Opcionais',
                            component: Option,
                        },
                        {
                            path     : '/admin/option/:target?',
                            name     : 'Cadastrar Opcional',
                            component: OptionForm,
                        }
                    ]
                },
                {
                    name     : 'Grupo de Opcionais',
                    path     : '/admin/groups',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Listar Grupo de Opcionais',
                            component: CategoriaOption,
                        },
                        {
                            path     : '/admin/group/:target?',
                            name     : 'Cadastrar Grupo de Opcionais',
                            component: CategoriaOptionForm,
                        }
                    ]
                },
                {
                    path     : '/admin/acabamentos',
                    name     : '',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Listar Acabamentos',
                            component: Acabamento,
                        },
                        {
                            path     : '/admin/acabamento/:target?',
                            name     : 'Visualizar Acabamentos',
                            component: AcabamentoForm,
                        }
                    ]
                },
                {
                    path     : '/admin/categoriasa',
                    name     : '',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Listar Categorias de Acabamentos',
                            component: Categoriaa,
                        },
                        {
                            path     : '/admin/categoriaa/:target?',
                            name     : 'Visualizar Categoria Acabamentos',
                            component: CategoriaaForm,
                        }
                    ]
                },
                {
                    path     : '/admin/orders',
                    name     : 'Orçamento',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Listar Orçamentos',
                            component: Order,
                        },
                        {
                            path     : '/admin/order/:target?',
                            name     : 'Visualizar Orçamento',
                            component: OrderView,
                        }
                    ]
                },
                {
                    path     : '/admin/home',
                    name     : 'Paginas',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Home',
                            component: Home,
                        }
                    ]
                },
                {
                    path     : '/admin/pageproduto',
                    name     : 'Paginas',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Page Produtos',
                            component: PageProduto,
                        }
                    ]
                },
                {
                    path     : '/admin/servicos',
                    name     : 'Paginas',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Página de Serviços',
                            component: Servicos,
                        }
                    ]
                },
                 {
                    path     : '/admin/contato',
                    name     : 'Paginas',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Página de Contato',
                            component: Contato,
                        }
                    ]
                },
                /*
                ---------------------------------------------------------
                aqui acaba a alteração
                ---------------------------------------------------------
                */
                {
                    path     : '/admin/common',
                    name     : 'Geral',
                    component: {
                        render(_) {
                            return _('router-view');
                        }
                    },
                    children : [
                        {
                            path     : '',
                            name     : 'Geral',
                            component: Common
                        }
                    ]
                },
            ],
        },
        {
            path     : '/admin/login',
            name     : 'Login',
            component: Login,
            meta     : {
                guest: true
            }
        },
    ]
});

export default router;

router.beforeEach(function (to, from, next) {
    let guestRoute = to.meta.guest || false;
    let redirected = false;

    _axios.get('auth/check')
          .then(() => {
              if (guestRoute) {
                  router.push({name: 'Dashboard'});

                  redirected = true;
              }
          })
          .catch(() => {
              if (!guestRoute) {
                  router.push({name: 'Login'});

                  redirected = true;
              }
          })
          .then(() => {
              if (!redirected) {
                  next();
              }
          });
});
