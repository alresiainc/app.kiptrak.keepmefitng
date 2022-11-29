  
// navigation = [
//     {
//       name: 'Dashboard',
//       id: 'dashboard',
//       url: './',
//       icon: 'bi bi-grid',
//       children: []
//     },      
//     {
//       name: 'Orders',
//       id: 'orders',
//       url: 'orders',
//       icon: 'bi bi-cart3',
//       children: [
//         {
//           name: 'Create Order',
//           id: 'create-order',
//           url: 'create-order',
//           icon: 'bi bi-plus',
//         },
//         {
//           name: 'View Orders',
//           id: 'view-orders',
//           url: 'orders',
//           icon: 'bi bi-cart3',
//         },
//       ]
//     },
//     {
//       name: 'Customers',
//       id: 'customers',
//       url: 'customers',
//       icon: 'bi bi-people',
//       children: [
//         {
//           name: 'Add Customer',
//           id: 'create-order',
//           url: 'create-order',
//           icon: 'bi bi-plus',
//         },
//         {
//           name: 'View Orders',
//           id: 'view-orders',
//           url: 'view-orders',
//           icon: 'bi bi-cart3',
//         },
//       ]
//     },  
//     {
//       name: 'Products',
//       id: 'products',
//       url: 'products',
//       icon: 'bi bi-card-list',
//       children: [
//         {
//           name: 'Add Product',
//           id: 'add-product',
//           url: 'add-product',
//           icon: 'bi bi-plus',
//         },
//         {
//           name: 'View Products',
//           id: 'view-products',
//           url: 'products',
//           icon: 'bi bi-card-list',
//         },
//       ]
//     }, 
//     {
//       name: 'Agents',
//       id: 'agents',
//       url: 'agents',
//       icon: 'bi bi-person',
//       children: [
//         {
//           name: 'Add Agent',
//           id: 'add-agent',
//           url: 'add-agent',
//           icon: 'bi bi-plus',
//         },
//         {
//           name: 'View Agents',
//           id: 'view-agents',
//           url: 'agents',
//           icon: 'bi bi-people-fill',
//         },
//       ]
//     },  
//   ];

//   if(navigation.length > 0){
//     var parentNode = document.getElementById('sidebar-nav');
    
//     for (let i = 0; i < navigation.length; i++) {
//       var item = navigation[i];

//       parentNode.innerHTML += `<li class="nav-item"><a class="nav-link ${item.children.length > 0 ? 'collapsed':''}" data-bs-target="#${item.id}-nav" ${item.children.length > 0 ? 'data-bs-toggle="collapse"':''} href="${item.children.length > 0 ? '#':item.url}"><i class="${item.icon}"></i><span>${item.name}</span>${item.children.length > 0 ? '<i class="bi bi-chevron-down ms-auto"></i>':''}</a> <ul id="${item.id}-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav"></ul></li>`;

//       if(item.children.length > 0){
//         for (let j = 0; j < item.children.length; j++) {
//           var childNode = document.getElementById(item.id+'-nav');
//           var child = item.children[j];
//           childNode.innerHTML += `<li><a href="${child.url}"><i style="font-size: 100%!important;" class="${child.icon}"></i><span>${child.name}</span></a></li>`;
//         }
//       }
      
//     }
//   }

