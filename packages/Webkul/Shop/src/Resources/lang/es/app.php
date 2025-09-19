<?php

return [
    'customers' => [
        'forgot-password' => [
            'already-sent'         => 'Ya se envió el correo de restablecimiento de contraseña.',
            'back'                 => 'Volver a iniciar sesión',
            'bagisto'              => 'Bagisto',
            'email'                => 'Correo electrónico',
            'email-not-exist'      => 'No podemos encontrar un usuario con esa dirección de correo electrónico.',
            'footer'               => '© Derechos de autor 2010 - :current_year, Webkul Software (Registrado en India). Todos los derechos reservados.',
            'forgot-password-text' => 'Si olvidó su contraseña, recupérela ingresando su dirección de correo electrónico.',
            'page-title'           => '¿Olvidó su contraseña?',
            'reset-link-sent'      => 'Hemos enviado por correo electrónico el enlace para restablecer su contraseña.',
            'sign-in-button'       => 'Iniciar sesión',
            'submit'               => 'Restablecer contraseña',
            'title'                => 'Recuperar contraseña',
        ],

        'reset-password' => [
            'back-link-title'  => 'Volver a Iniciar Sesión',
            'bagisto'          => 'Bagisto',
            'confirm-password' => 'Confirmar Contraseña',
            'email'            => 'Correo Electrónico Registrado',
            'footer'           => '© Derechos de autor 2010 - :current_year, Webkul Software (Registrada en India). Todos los derechos reservados.',
            'password'         => 'Contraseña',
            'submit-btn-title' => 'Restablecer Contraseña',
            'title'            => 'Restablecer Contraseña',
        ],

        'login-form' => [
            'bagisto'             => 'Bagisto',
            'button-title'        => 'Iniciar Sesión',
            'create-your-account' => 'Crea tu cuenta',
            'email'               => 'Correo Electrónico',
            'footer'              => '© Derechos de autor 2010 - :current_year, Webkul Software (Registrada en India). Todos los derechos reservados.',
            'forgot-pass'         => '¿Olvidaste tu Contraseña?',
            'form-login-text'     => 'Si tienes una cuenta, inicia sesión con tu dirección de correo electrónico.',
            'invalid-credentials' => 'Por favor, verifica tus credenciales e inténtalo de nuevo.',
            'new-customer'        => '¿Nuevo cliente?',
            'not-activated'       => 'Tu activación requiere la aprobación del administrador.',
            'page-title'          => 'Inicio de Sesión del Cliente',
            'password'            => 'Contraseña',
            'show-password'       => 'Mostrar Contraseña',
            'title'               => 'Iniciar Sesión',
            'verify-first'        => 'Verifica primero tu cuenta de correo electrónico.',
        ],

        'signup-form' => [
            'account-exists'              => '¿Ya tienes una cuenta?',
            'bagisto'                     => 'Bagisto',
            'button-title'                => 'Registrarse',
            'click-here'                  => 'Haga clic aquí',
            'confirm-pass'                => 'Confirmar Contraseña',
            'email'                       => 'Correo Electrónico',
            'first-name'                  => 'Nombre',
            'footer'                      => '© Derechos de autor 2010 - :current_year, Webkul Software (Registrada en India). Todos los derechos reservados.',
            'form-signup-text'            => 'Si eres nuevo en nuestra tienda, estamos encantados de tenerte como miembro.',
            'last-name'                   => 'Apellido',
            'page-title'                  => 'Convertirse en Usuario',
            'password'                    => 'Contraseña',
            'sign-in-button'              => 'Iniciar Sesión',
            'subscribe-to-newsletter'     => 'Suscribirse al boletín',
            'success'                     => 'Cuenta creada con éxito.',
            'success-verify'              => 'Cuenta creada con éxito, se ha enviado un correo electrónico para su verificación.',
            'terms-conditions'            => 'Términos y Condiciones',
            'verification-not-sent'       => 'Error! Problema al enviar el correo de verificación, por favor inténtalo de nuevo más tarde.',
            'verification-sent'           => 'Correo de verificación enviado',
            'verified'                    => 'Tu cuenta ha sido verificada, intenta iniciar sesión ahora.',
            'verify-failed'               => 'No podemos verificar tu cuenta de correo.',
        ],

        'account' => [
            'home' => 'Inicio',

            'profile' => [
                'index' => [
                    'delete'         => 'Eliminar',
                    'delete-failed'  => 'Error encontrado al eliminar el cliente.',
                    'delete-profile' => 'Eliminar Perfil',
                    'delete-success' => 'Cliente eliminado exitosamente',
                    'dob'            => 'Fecha de Nacimiento',
                    'edit'           => 'Editar',
                    'edit-success'   => 'Perfil actualizado exitosamente',
                    'email'          => 'Correo Electrónico',
                    'enter-password' => 'Ingresa tu contraseña',
                    'first-name'     => 'Nombre',
                    'gender'         => 'Género',
                    'last-name'      => 'Apellido',
                    'order-pending'  => 'No se puede eliminar la cuenta del cliente porque hay pedidos pendientes o en estado de procesamiento.',
                    'title'          => 'Perfil',
                    'unmatched'      => 'La contraseña antigua no coincide.',
                    'wrong-password' => '¡Contraseña incorrecta!',
                ],

                'edit' => [
                    'confirm-password'        => 'Confirmar Contraseña',
                    'current-password'        => 'Contraseña Actual',
                    'dob'                     => 'Fecha de Nacimiento',
                    'edit'                    => 'Editar',
                    'edit-profile'            => 'Editar Perfil',
                    'email'                   => 'Correo Electrónico',
                    'female'                  => 'Femenino',
                    'first-name'              => 'Nombre',
                    'gender'                  => 'Género',
                    'last-name'               => 'Apellido',
                    'male'                    => 'Masculino',
                    'new-password'            => 'Nueva Contraseña',
                    'other'                   => 'Otro',
                    'phone'                   => 'Teléfono',
                    'save'                    => 'Guardar',
                    'subscribe-to-newsletter' => 'Suscribirse al boletín',
                ],
            ],

            'addresses' => [
                'index' => [
                    'add-address'      => 'Agregar Dirección',
                    'create-success'   => 'La dirección se ha agregado correctamente.',
                    'default-address'  => 'Dirección Predeterminada',
                    'default-delete'   => 'No se puede cambiar la dirección predeterminada.',
                    'delete'           => 'Eliminar',
                    'delete-success'   => 'Dirección eliminada correctamente',
                    'edit'             => 'Editar',
                    'edit-success'     => 'Dirección actualizada correctamente.',
                    'empty-address'    => 'Aún no has agregado una dirección a tu cuenta.',
                    'security-warning' => '¡Se ha detectado actividad sospechosa!',
                    'set-as-default'   => 'Establecer como Predeterminada',
                    'title'            => 'Dirección',
                    'update-success'   => 'La dirección se ha actualizado correctamente.',
                ],

                'create' => [
                    'add-address'    => 'Agregar Dirección',
                    'city'           => 'Ciudad',
                    'company-name'   => 'Nombre de la Empresa',
                    'country'        => 'País',
                    'email'          => 'Correo Electrónico',
                    'first-name'     => 'Nombre',
                    'last-name'      => 'Apellido',
                    'phone'          => 'Teléfono',
                    'post-code'      => 'Código Postal',
                    'save'           => 'Guardar',
                    'select-country' => 'Seleccionar País',
                    'set-as-default' => 'Establecer como Predeterminada',
                    'state'          => 'Estado',
                    'street-address' => 'Dirección',
                    'title'          => 'Dirección',
                    'vat-id'         => 'ID de IVA',
                ],

                'edit' => [
                    'city'           => 'Ciudad',
                    'company-name'   => 'Nombre de la Empresa',
                    'country'        => 'País',
                    'edit'           => 'Editar',
                    'email'          => 'Correo Electrónico',
                    'first-name'     => 'Nombre',
                    'last-name'      => 'Apellido',
                    'phone'          => 'Teléfono',
                    'post-code'      => 'Código Postal',
                    'select-country' => 'Seleccionar País',
                    'state'          => 'Estado',
                    'street-address' => 'Dirección',
                    'title'          => 'Dirección',
                    'update-btn'     => 'Actualizar',
                    'vat-id'         => 'ID de IVA',
                ],
            ],

            'orders' => [
                'action'      => 'Acción',
                'action-view' => 'Ver',
                'empty-order' => 'Aún no ha realizado ningún pedido',
                'order'       => 'Pedido',
                'order-date'  => 'Fecha del pedido',
                'order-id'    => 'ID de pedido',
                'subtotal'    => 'Subtotal',
                'title'       => 'Pedidos',
                'total'       => 'Total',

                'status' => [
                    'title' => 'Estado',

                    'options' => [
                        'canceled'        => 'Cancelado',
                        'closed'          => 'Cerrado',
                        'completed'       => 'Completado',
                        'fraud'           => 'Fraude',
                        'pending'         => 'Pendiente',
                        'pending-payment' => 'Pago Pendiente',
                        'processing'      => 'Procesando',
                    ],
                ],

                'view' => [
                    'billing-address'      => 'Dirección de Facturación',
                    'cancel-btn-title'     => 'Cancelar',
                    'cancel-confirm-msg'   => '¿Estás seguro de que quieres cancelar este pedido?',
                    'cancel-error'         => 'No se puede cancelar tu pedido.',
                    'cancel-success'       => 'Tu pedido ha sido cancelado',
                    'contact'              => 'Contacto',
                    'item-invoiced'        => 'Producto Facturado',
                    'item-refunded'        => 'Producto Reembolsado',
                    'item-shipped'         => 'Producto Enviado',
                    'item-ordered'         => 'Producto Pedido',
                    'order-id'             => 'ID de Pedido',
                    'page-title'           => 'Pedido #:order_id',
                    'payment-method'       => 'Método de Pago',
                    'reorder-btn-title'    => 'Volver a Pedir',
                    'shipping-address'     => 'Dirección de Envío',
                    'shipping-method'      => 'Método de Envío',
                    'shipping-and-payment' => 'Detalles de Envío y Pago',
                    'status'               => 'Estado',
                    'title'                => 'Ver',
                    'total'                => 'Total',

                    'information' => [
                        'discount'                   => 'Descuento',
                        'excl-tax'                   => 'Excl. Impuestos:',
                        'grand-total'                => 'Total General',
                        'info'                       => 'Información',
                        'item-canceled'              => 'Cancelado (:qty_canceled)',
                        'item-refunded'              => 'Reembolsado (:qty_refunded)',
                        'invoiced-item'              => 'Facturado (:qty_invoiced)',
                        'item-shipped'               => 'Enviado (:qty_shipped)',
                        'item-status'                => 'Estado del Producto',
                        'ordered-item'               => 'Pedido (:qty_ordered)',
                        'placed-on'                  => 'Realizado el',
                        'price'                      => 'Precio',
                        'product-name'               => 'Nombre',
                        'shipping-handling'          => 'Envío y Manipulación',
                        'shipping-handling-excl-tax' => 'Envío y Manipulación (Excl. Impuestos)',
                        'shipping-handling-incl-tax' => 'Envío y Manipulación (Incl. Impuestos)',
                        'sku'                        => 'SKU',
                        'subtotal'                   => 'Subtotal',
                        'subtotal-excl-tax'          => 'Subtotal (Excl. Impuestos)',
                        'subtotal-incl-tax'          => 'Subtotal (Incl. Impuestos)',
                        'order-summary'              => 'Resumen del Pedido',
                        'tax'                        => 'Impuestos',
                        'tax-amount'                 => 'Monto de Impuestos',
                        'tax-percent'                => 'Porcentaje de Impuestos',
                        'total-due'                  => 'Total a Pagar',
                        'total-paid'                 => 'Total Pagado',
                        'total-refunded'             => 'Total Reembolsado',
                    ],

                    'invoices' => [
                        'discount'                   => 'Descuento',
                        'excl-tax'                   => 'Excl. Impuestos:',
                        'grand-total'                => 'Total General',
                        'individual-invoice'         => 'Factura #:invoice_id',
                        'invoices'                   => 'Facturas',
                        'price'                      => 'Precio',
                        'print'                      => 'Imprimir',
                        'product-name'               => 'Nombre',
                        'products-ordered'           => 'Productos Pedidos',
                        'qty'                        => 'Cantidad',
                        'shipping-handling-excl-tax' => 'Envío y Manipulación (Excl. Impuestos)',
                        'shipping-handling-incl-tax' => 'Envío y Manipulación (Incl. Impuestos)',
                        'shipping-handling'          => 'Envío y Manipulación',
                        'sku'                        => 'SKU',
                        'subtotal-excl-tax'          => 'Subtotal (Excl. Impuestos)',
                        'subtotal-incl-tax'          => 'Subtotal (Incl. Impuestos)',
                        'subtotal'                   => 'Subtotal',
                        'tax'                        => 'Impuestos',
                        'tax-amount'                 => 'Monto de Impuestos',
                    ],

                    'shipments' => [
                        'individual-shipment' => 'Envío #:shipment_id',
                        'product-name'        => 'Nombre',
                        'qty'                 => 'Cantidad',
                        'shipments'           => 'Envíos',
                        'sku'                 => 'SKU',
                        'subtotal'            => 'Subtotal',
                        'tracking-number'     => 'Número de Seguimiento',
                    ],

                    'refunds' => [
                        'adjustment-fee'             => 'Tarifa de Ajuste',
                        'adjustment-refund'          => 'Reembolso de Ajuste',
                        'discount'                   => 'Descuento',
                        'grand-total'                => 'Total General',
                        'individual-refund'          => 'Reembolso #:refund_id',
                        'no-result-found'            => 'No se encontraron registros.',
                        'order-summary'              => 'Resumen del Pedido',
                        'price'                      => 'Precio',
                        'product-name'               => 'Nombre',
                        'qty'                        => 'Cantidad',
                        'refunds'                    => 'Reembolsos',
                        'shipping-handling'          => 'Envío y Manipulación',
                        'shipping-handling-excl-tax' => 'Envío y Manipulación (Excl. Impuestos)',
                        'shipping-handling-incl-tax' => 'Envío y Manipulación (Incl. Impuestos)',
                        'sku'                        => 'SKU',
                        'subtotal'                   => 'Subtotal',
                        'subtotal-excl-tax'          => 'Subtotal (Excl. Impuestos)',
                        'subtotal-incl-tax'          => 'Subtotal (Incl. Impuestos)',
                        'tax'                        => 'Impuestos',
                        'tax-amount'                 => 'Monto de Impuestos',
                    ],
                ],

                'invoice-pdf' => [
                    'bank-details'               => 'Detalles Bancarios',
                    'bill-to'                    => 'Facturar a',
                    'contact-number'             => 'Número de Contacto',
                    'contact'                    => 'Contacto',
                    'date'                       => 'Fecha de la Factura',
                    'discount'                   => 'Descuento',
                    'excl-tax'                   => 'Excl. Impuestos:',
                    'grand-total'                => 'Total General',
                    'invoice-id'                 => 'ID de Factura',
                    'invoice'                    => 'Factura',
                    'order-date'                 => 'Fecha del Pedido',
                    'order-id'                   => 'ID de Pedido',
                    'payment-method'             => 'Método de Pago',
                    'payment-terms'              => 'Términos de Pago',
                    'price'                      => 'Precio',
                    'product-name'               => 'Nombre del Producto',
                    'qty'                        => 'Cantidad',
                    'ship-to'                    => 'Enviar a',
                    'shipping-handling-excl-tax' => 'Envío y Manejo (Excl. Impuestos)',
                    'shipping-handling-incl-tax' => 'Envío y Manejo (Incl. Impuestos)',
                    'shipping-handling'          => 'Envío y Manejo',
                    'shipping-method'            => 'Método de Envío',
                    'sku'                        => 'SKU',
                    'subtotal-excl-tax'          => 'Subtotal (Excl. Impuestos)',
                    'subtotal-incl-tax'          => 'Subtotal (Incl. Impuestos)',
                    'subtotal'                   => 'Subtotal',
                    'tax-amount'                 => 'Monto de Impuestos',
                    'tax'                        => 'Impuestos',
                    'vat-number'                 => 'Número de IVA',
                ],
            ],

            'reviews' => [
                'empty-review' => 'Todavía no has revisado ningún producto',
                'title'        => 'Opiniones',
            ],

            'downloadable-products' => [
                'available'           => 'Disponible',
                'completed'           => 'Terminado',
                'date'                => 'Fecha',
                'download-error'      => 'El enlace de descarga ha expirado.',
                'expired'             => 'Caducado',
                'empty-product'       => 'No tienes productos para descargar',
                'name'                => 'Productos Descargables',
                'orderId'             => 'ID de Pedido',
                'pending'             => 'Pendiente',
                'payment-error'       => 'El pago no se ha realizado para esta descarga.',
                'records-found'       => 'Registro(s) encontrado(s)',
                'remaining-downloads' => 'Descargas Restantes',
                'status'              => 'Estado',
                'title'               => 'Título',
            ],

            'wishlist' => [
                'color'              => 'Color',
                'delete-all'         => 'Eliminar Todos',
                'empty'              => 'No se han añadido productos a la página de deseos.',
                'move-to-cart'       => 'Mover al Carrito',
                'moved'              => 'Artículo movido exitosamente al carrito',
                'moved-success'      => 'Artículo movido exitosamente al carrito',
                'page-title'         => 'Lista de Deseos',
                'product-removed'    => 'El producto ya no está disponible, ha sido eliminado por el administrador',
                'profile'            => 'Perfil',
                'remove'             => 'Eliminar',
                'remove-all-success' => 'Todos los artículos de tu lista de deseos han sido eliminados',
                'remove-fail'        => 'El artículo no se puede eliminar de la lista de deseos',
                'removed'            => 'Artículo eliminado exitosamente de la lista de deseos',
                'see-details'        => 'Ver Detalles',
                'success'            => 'Artículo añadido exitosamente a la lista de deseos',
                'title'              => 'Lista de Deseos',
            ],

            'gdpr' => [
                'create-success'              => 'Solicitud creada con éxito',
                'revoked-successfully'        => 'Solicitud revocada con éxito',
                'success-verify'              => '¡Éxito! El correo de verificación ha sido enviado.',
                'success-verify-email-unsent' => '¡Éxito! El correo de verificación no ha sido enviado.',
                'unable-to-sent'              => 'No se pudo enviar el correo electrónico.',

                'index'   => [
                    'create-btn' => 'Crear solicitud',
                    'html'       => 'HTML',
                    'pdf'        => 'PDF',
                    'title'      => 'Solicitudes de datos GDPR',

                    'modal' => [
                        'message' => 'Mensaje',
                        'save'    => 'Guardar',
                        'title'   => 'Crear nueva solicitud',

                        'type'    => [
                            'choose' => 'Elegir',
                            'delete' => 'Eliminar',
                            'title'  => 'Tipo',
                            'update' => 'Actualizar',
                        ],
                    ],

                    'datagrid' => [
                        'completed'  => 'Completado',
                        'date'       => 'Fecha',
                        'declined'   => 'Rechazado',
                        'delete'     => 'Eliminar',
                        'id'         => 'ID',
                        'message'    => 'Mensaje',
                        'pending'    => 'Pendiente',
                        'processing' => 'Procesando',
                        'revoke-btn' => 'Revocar',
                        'revoked'    => 'Revocado',
                        'status'     => 'Estado',
                        'type'       => 'Tipo',
                        'update'     => 'Actualizar',
                    ],
                ],

                'pdf' => [
                    'title' => 'Vista predeterminada de la tienda',

                    'account-info' => [
                        'dob'          => 'Fecha de nacimiento',
                        'email'        => 'Correo electrónico',
                        'first-name'   => 'Nombre',
                        'gender'       => 'Género',
                        'last-name'    => 'Apellido',
                        'phone'        => 'Teléfono',
                        'title'        => 'Información de la cuenta',
                    ],

                    'address-info' => [
                        'address'    => 'Dirección',
                        'address1'   => 'Dirección 1',
                        'address2'   => 'Dirección 2',
                        'city'       => 'Ciudad',
                        'company'    => 'Empresa',
                        'country'    => 'País',
                        'first-name' => 'Nombre',
                        'last-name'  => 'Apellido',
                        'phone'      => 'Teléfono',
                        'postcode'   => 'Código postal',
                        'state'      => 'Estado',
                        'title'      => 'Información de la dirección',
                        'vat-id'     => 'ID de IVA',
                    ],

                    'order-info' => [
                        'amount'       => 'Monto',
                        'order-id'     => 'ID de pedido',
                        'product-name' => 'Nombre del producto',
                        'qty'          => 'Cantidad',
                        'shipping'     => 'Envío',
                        'sku'          => 'SKU',
                        'status'       => 'Estado',
                        'title'        => 'Información del pedido',
                        'type'         => 'Tipo',
                    ],
                ],
            ],
        ],
    ],

    'components' => [
        'accordion' => [
            'default-content' => 'Contenido predeterminado',
            'default-header'  => 'Encabezado predeterminado',
        ],

        'drawer' => [
            'default-toggle' => 'Alternar predeterminado',
        ],

        'media' => [
            'index' => [
                'add-attachments' => 'Agregar adjuntos',
                'add-image'       => 'Agregar Imagen/Video',
            ],
        ],

        'layouts' => [
            'header' => [
                'desktop' => [
                    'top' => [
                        'default-locale' => 'Idioma predeterminado',
                    ],

                    'bottom' => [
                        'all'           => 'Todo',
                        'back-button'   => 'Volver al menú principal',
                        'bagisto'       => 'Bagisto',
                        'categories'    => 'Categorías',
                        'compare'       => 'Comparar',
                        'dropdown-text' => 'Gestionar carrito, pedidos y lista de deseos',
                        'logout'        => 'Cerrar sesión',
                        'orders'        => 'Pedidos',
                        'profile'       => 'Perfil',
                        'search'        => 'Buscar',
                        'search-text'   => 'Buscar productos aquí',
                        'sign-in'       => 'Iniciar sesión',
                        'sign-up'       => 'Registrarse',
                        'submit'        => 'Enviar',
                        'welcome'       => 'Bienvenido',
                        'welcome-guest' => 'Bienvenido Invitado',
                        'wishlist'      => 'Lista de deseos',
                    ],
                ],

                'mobile' => [
                    'account'       => 'Cuenta',
                    'back-button'   => 'Volver al menú principal',
                    'bagisto'       => 'Bagisto',
                    'compare'       => 'Comparar',
                    'currencies'    => 'Monedas',
                    'dropdown-text' => 'Gestionar carrito, pedidos y lista de deseos',
                    'locales'       => 'Idiomas',
                    'login'         => 'Registrarse o iniciar sesión',
                    'logout'        => 'Cerrar sesión',
                    'orders'        => 'Pedidos',
                    'profile'       => 'Perfil',
                    'search'        => 'Buscar',
                    'search-text'   => 'Buscar productos aquí',
                    'sign-in'       => 'Iniciar sesión',
                    'sign-up'       => 'Registrarse',
                    'welcome'       => 'Bienvenido',
                    'welcome-guest' => 'Bienvenido Invitado',
                    'wishlist'      => 'Lista de deseos',
                ],
            ],

            'footer' => [
                'about-us'               => 'Acerca de nosotros',
                'contact-us'             => 'Contacto',
                'currency'               => 'Moneda',
                'customer-service'       => 'Servicio al cliente',
                'email'                  => 'Correo Electrónico',
                'footer-content'         => 'Contenido del pie de página',
                'footer-text'            => '© Derechos de autor 2010 - :current_year, Webkul Software (registrada en India). Todos los derechos reservados.',
                'locale'                 => 'Idioma',
                'newsletter-text'        => '¡Prepárate para nuestro divertido boletín!',
                'order-return'           => 'Pedido y devolución',
                'payment-policy'         => 'Política de pagos',
                'privacy-cookies-policy' => 'Política de privacidad y cookies',
                'shipping-policy'        => 'Política de envíos',
                'subscribe'              => 'Suscribirse',
                'subscribe-newsletter'   => 'Suscribirse al boletín',
                'subscribe-stay-touch'   => 'Suscríbete para mantenerte en contacto.',
                'whats-new'              => 'Novedades',
            ],

            'cookie' => [
                'index' => [
                    'privacy-policy'           => 'Política de Privacidad',
                    'reject'                   => 'Rechazar',
                    'accept'                   => 'Aceptar',
                    'learn-more-and-customize' => 'Aprender Más y Personalizar',
                ],

                'consent' => [
                    'your-cookie-consent-preferences'         => 'Tus Preferencias de Consentimiento de Cookies',
                    'save-and-continue'                       => 'Guardar y Continuar',
                    'strictly-necessary'                      => 'Estríctamente Necesarias',
                    'basic-interactions'                      => 'Interacciones y Funcionalidades Básicas',
                    'experience-enhancement'                  => 'Mejora de la Experiencia',
                    'measurements'                            => 'Medición',
                    'targeting-and-advertising'               => 'Segmentación y Publicidad',
                ],
            ],
        ],

        'datagrid' => [
            'toolbar' => [
                'length-of' => ':length de',
                'results'   => ':total resultados',
                'selected'  => ':total seleccionados',

                'mass-actions' => [
                    'must-select-a-mass-action'        => 'Debe seleccionar una acción masiva.',
                    'must-select-a-mass-action-option' => 'Debe seleccionar una opción para la acción masiva.',
                    'no-records-selected'              => 'No se han seleccionado registros.',
                    'select-action'                    => 'Seleccionar acción',
                ],

                'search' => [
                    'title' => 'Buscar',
                ],

                'filter' => [
                    'apply-filter' => 'Aplicar filtro',
                    'title'        => 'Filtro',

                    'dropdown' => [
                        'select' => 'Seleccionar',

                        'searchable' => [
                            'at-least-two-chars' => 'Ingrese al menos 2 caracteres...',
                            'no-results'         => 'Sin resultados...',
                        ],
                    ],

                    'custom-filters' => [
                        'clear-all' => 'Limpiar todo',
                    ],
                ],
            ],

            'table' => [
                'actions'              => 'Acciones',
                'next-page'            => 'Siguiente página',
                'no-records-available' => 'No hay registros disponibles.',
                'of'                   => 'de :total registros',
                'page-navigation'      => 'Navegación de página',
                'page-number'          => 'Número de página',
                'previous-page'        => 'Página anterior',
                'showing'              => 'Mostrando :firstItem',
                'to'                   => 'hasta :lastItem',
            ],
        ],

        'modal' => [
            'default-content' => 'Contenido predeterminado',
            'default-header'  => 'Encabezado predeterminado',

            'confirm' => [
                'agree-btn'    => 'Aceptar',
                'disagree-btn' => 'No estar de acuerdo',
                'message'      => '¿Estás seguro de que quieres realizar esta acción?',
                'title'        => '¿Estás seguro?',
            ],
        ],

        'products' => [
            'card' => [
                'add-to-cart'            => 'Agregar al carrito',
                'add-to-compare'         => 'Agregar a comparar',
                'add-to-compare-success' => 'Elemento añadido correctamente a la lista de comparación.',
                'add-to-wishlist'        => 'Agregar a la lista de deseos',
                'already-in-compare'     => 'El elemento ya está agregado a la lista de comparación.',
                'new'                    => 'Nuevo',
                'review-description'     => 'Sé el primero en revisar este producto',
                'sale'                   => 'Venta',
            ],

            'carousel' => [
                'next'     => 'Siguiente',
                'previous' => 'Anterior',
                'view-all' => 'Ver todo',
            ],

            'ratings' => [
                'title' => 'Calificaciones',
            ],
        ],

        'range-slider' => [
            'max-range' => 'Rango máximo',
            'min-range' => 'Rango mínimo',
            'range'     => 'Rango:',
        ],

        'carousel' => [
            'image-slide' => 'Diapositiva de imagen',
            'next'        => 'Siguiente',
            'previous'    => 'Anterior',
        ],

        'quantity-changer' => [
            'decrease-quantity' => 'Disminuir cantidad',
            'increase-quantity' => 'Aumentar cantidad',
        ],
    ],

    'products' => [
        'prices' => [
            'grouped' => [
                'starting-at' => 'A partir de',
            ],

            'configurable' => [
                'as-low-as' => 'Desde',
            ],
        ],

        'sort-by' => [
            'title'   => 'Ordenar por',
        ],

        'view' => [
            'type' => [
                'simple' => [
                    'customizable-options' => [
                        'none'         => 'Ninguno',
                        'total-amount' => 'Cantidad Total',
                    ],
                ],

                'configurable' => [
                    'select-options'       => 'Por favor, seleccione una opción',
                    'select-above-options' => 'Por favor, seleccione las opciones anteriores',
                ],

                'bundle' => [
                    'none'         => 'Ninguno',
                    'total-amount' => 'Monto Total',
                ],

                'downloadable' => [
                    'links'   => 'Enlaces',
                    'samples' => 'Muestras',
                    'sample'  => 'Muestra',
                ],

                'grouped' => [
                    'name' => 'Nombre',
                ],

                'booking' => [
                    'location'    => 'Ubicación',
                    'view-on-map' => 'Ver en el Mapa',

                    'default' => [
                        'slot-duration-in-minutes' => ':minutes Minutos',
                        'slot-duration'            => 'Duración del Intervalo',
                    ],

                    'appointment' => [
                        'closed'                   => 'Cerrado',
                        'see-details'              => 'Ver Detalles',
                        'slot-duration'            => 'Duración del Intervalo',
                        'slot-duration-in-minutes' => ':minutes Minutos',
                        'today-availability'       => 'Disponibilidad de Hoy',
                    ],

                    'event' => [
                        'book-your-ticket' => 'Reserva tu Entrada',
                        'title'            => 'Evento en :',
                    ],

                    'rental'      => [
                        'choose-rent-option' => 'Elige Opción de Alquiler',
                        'daily-basis'        => 'Diario',
                        'from'               => 'Desde',
                        'hourly-basis'       => 'Por Hora',
                        'rent-an-item'       => 'Alquilar un Artículo',
                        'select-date'        => 'Seleccionar Fecha',
                        'select-rent-time'   => 'Seleccionar Hora de Alquiler',
                        'select-slot'        => 'Seleccionar Intervalo',
                        'select-time-slot'   => 'Seleccionar Intervalo de Tiempo',
                        'slot'               => 'Intervalo',
                        'no-slots-available' => 'No hay intervalos disponibles',
                        'to'                 => 'Hasta',
                    ],

                    'table'       => [
                        'book-a-table'             => 'Reservar una Mesa',
                        'closed'                   => 'Cerrado',
                        'slot-duration'            => 'Duración del Intervalo',
                        'slot-duration-in-minutes' => ':minutes Minutos',
                        'slots-for-all-days'       => 'Mostrar para todos los días',
                        'special-notes'            => 'Solicitud/Notas Especiales',
                        'today-availability'       => 'Disponibilidad de Hoy',
                    ],

                    'slots' => [
                        'book-an-appointment' => 'Reservar una Cita',
                        'date'                => 'Fecha',
                        'no-slots-available'  => 'No hay intervalos disponibles',
                        'select-slot'         => 'Seleccionar Intervalo',
                        'title'               => 'Intervalo',
                    ],
                ],
            ],

            'gallery' => [
                'product-image'   => 'Imagen del producto',
                'thumbnail-image' => 'Imagen en miniatura',
            ],

            'reviews' => [
                'attachments'      => 'Adjuntos',
                'cancel'           => 'Cancelar',
                'comment'          => 'Comentario',
                'customer-review'  => 'Opiniones de clientes',
                'empty-review'     => 'No se encontraron reseñas, sé el primero en calificar este producto.',
                'failed-to-upload' => 'La imagen no se pudo cargar',
                'load-more'        => 'Cargar más',
                'name'             => 'Nombre',
                'rating'           => 'Calificación',
                'ratings'          => 'Calificaciones',
                'submit-review'    => 'Enviar reseña',
                'success'          => 'Revisión enviada con éxito.',
                'title'            => 'Título',
                'translate'        => 'Traducir',
                'translating'      => 'Traduciendo...',
                'write-a-review'   => 'Escribir una reseña',
            ],

            'add-to-cart'            => 'Agregar al carrito',
            'add-to-compare'         => 'Producto añadido a la lista de comparación.',
            'add-to-wishlist'        => 'Agregar a la lista de deseos',
            'additional-information' => 'Información adicional',
            'already-in-compare'     => 'El producto ya está en la lista de comparación.',
            'buy-now'                => 'Comprar ahora',
            'compare'                => 'Comparar',
            'description'            => 'Descripción',
            'related-product-title'  => 'Productos relacionados',
            'review'                 => 'Reseñas',
            'tax-inclusive'          => 'Incluye todos los impuestos',
            'up-sell-title'          => 'Hemos encontrado otros productos que podrían gustarte.',
        ],

        'type' => [
            'abstract' => [
                'offers' => 'Compra :qty a :price cada uno y ahorra :discount',
            ],
        ],

        'booking' => [
            'closed' => 'Cerrado',

            'cart'             => [
                'booking-from' => 'Reserva Desde',
                'booking-till' => 'Reserva Hasta',
                'daily'        => 'Base Diaria',
                'event-from'   => 'Evento Desde',
                'event-ticket' => 'Entrada para Evento',
                'event-till'   => 'Evento Hasta',
                'hourly'       => 'Base por Hora',

                'integrity'    => [
                    'event'                  => [
                        'expired' => 'Este evento ha expirado.',
                    ],

                    'missing_options'        => 'Faltan opciones para este producto.',
                    'inventory_warning'      => 'La cantidad solicitada no está disponible, por favor inténtalo de nuevo más tarde.',
                    'select_hourly_duration' => 'Selecciona una duración de una hora.',
                ],

                'rent-from'    => 'Alquiler Desde',
                'rent-till'    => 'Alquiler Hasta',
                'rent-type'    => 'Tipo de Alquiler',
                'renting_type' => 'Tipo de Alquiler',
                'special-note' => 'Solicitud/Notas Especiales',
            ],

            'per-ticket-price' => ':price Por Entrada',
        ],
    ],

    'categories' => [
        'filters' => [
            'clear-all' => 'Borrar todo',
            'filter'    => 'Filtrar',
            'filters'   => 'Filtros:',

            'search' => [
                'load-more'            => 'Cargar más',
                'loading'              => 'Cargando...',
                'no-options-available' => 'No hay opciones disponibles.',
                'results-info'         => 'Mostrando :currentCount de :totalCount opciones',
                'title'                => 'Buscar',
            ],

            'sort'      => 'Ordenar',
        ],

        'toolbar' => [
            'grid' => 'Cuadrícula',
            'list' => 'Lista',
            'show' => 'Mostrar',
        ],

        'view' => [
            'empty'     => 'No hay productos disponibles en esta categoría',
            'load-more' => 'Cargar más',
        ],
    ],

    'search' => [
        'title'   => 'Estos son los resultados para : :query',
        'suggest' => 'Buscar en su lugar',
        'results' => 'Resultados de búsqueda',

        'images' => [
            'index' => [
                'only-images-allowed'  => 'Solo se permiten imágenes (.jpeg, .jpg, .png, ..).',
                'search'               => 'Buscar',
                'size-limit-error'     => 'Error de límite de tamaño',
                'something-went-wrong' => 'Algo salió mal, por favor inténtelo de nuevo más tarde.',
            ],

            'results' => [
                'analyzed-keywords' => 'Palabras clave analizadas:',
            ],
        ],
    ],

    'compare' => [
        'already-added'      => 'El artículo ya ha sido añadido a la lista de comparación',
        'delete-all'         => 'Borrar todo',
        'empty-text'         => 'No tienes artículos en tu lista de comparación',
        'item-add-success'   => 'El artículo se ha añadido correctamente a la lista de comparación',
        'product-compare'    => 'Comparación de productos',
        'remove-all-success' => 'Todos los artículos fueron eliminados correctamente.',
        'remove-error'       => 'Algo salió mal. Por favor, inténtalo de nuevo más tarde.',
        'remove-success'     => 'El artículo se ha eliminado correctamente.',
        'title'              => 'Comparación de productos',
    ],

    'checkout' => [
        'success' => [
            'info'          => 'Le enviaremos los detalles de su pedido y la información de seguimiento por correo electrónico',
            'order-id-info' => 'Su número de pedido es #:order_id',
            'thanks'        => '¡Gracias por su pedido!',
            'title'         => 'Pedido realizado con éxito',
        ],

        'cart' => [
            'continue-to-checkout'      => 'Continuar con el Pago',
            'illegal'                   => 'La cantidad no puede ser menor que uno.',
            'inactive-add'              => 'El artículo inactivo no se puede agregar al carrito.',
            'inactive'                  => 'El artículo ha sido desactivado y posteriormente eliminado del carrito.',
            'inventory-warning'         => 'La cantidad solicitada no está disponible, por favor inténtalo de nuevo más tarde.',
            'item-add-to-cart'          => 'Producto Agregado Exitosamente',
            'minimum-order-message'     => 'El monto mínimo de la orden es',
            'missing-fields'            => 'Faltan algunos campos obligatorios para este producto.',
            'missing-options'           => 'Faltan opciones para este producto.',
            'paypal-payment-cancelled'  => 'El pago de Paypal ha sido cancelado.',
            'qty-missing'               => 'Al menos un producto debe tener una cantidad mayor que 1.',
            'return-to-shop'            => 'Volver a la Tienda',
            'rule-applied'              => 'Regla de carrito aplicada',
            'select-hourly-duration'    => 'Selecciona una duración de una hora.',
            'success-remove'            => 'El artículo se ha eliminado correctamente del carrito.',
            'suspended-account-message' => 'Tu cuenta ha sido suspendida.',

            'index' => [
                'bagisto'                  => 'Bagisto',
                'cart'                     => 'Carrito',
                'continue-shopping'        => 'Continuar Comprando',
                'empty-product'            => 'No tienes productos en tu carrito.',
                'excl-tax'                 => 'Excl. Impuestos:',
                'home'                     => 'Inicio',
                'items-selected'           => ':count Artículos Seleccionados',
                'move-to-wishlist'         => 'Mover a la Lista de Deseos',
                'move-to-wishlist-success' => 'Los artículos seleccionados se han movido con éxito a la lista de deseos.',
                'price'                    => 'Precio',
                'product-name'             => 'Nombre del Producto',
                'quantity'                 => 'Cantidad',
                'quantity-update'          => 'Cantidad actualizada con éxito',
                'remove'                   => 'Eliminar',
                'remove-selected-success'  => 'Los artículos seleccionados se han eliminado correctamente del carrito.',
                'see-details'              => 'Ver Detalles',
                'select-all'               => 'Seleccionar todo',
                'select-cart-item'         => 'Seleccionar artículo del carrito',
                'tax'                      => 'Impuesto',
                'total'                    => 'Total',
                'update-cart'              => 'Actualizar Carrito',
                'view-cart'                => 'Ver Carrito',

                'cross-sell' => [
                    'title' => 'Más opciones',
                ],
            ],

            'mini-cart' => [
                'continue-to-checkout' => 'Continuar con el Pago',
                'empty-cart'           => 'Tu carrito está vacío',
                'excl-tax'             => 'Excl. Impuestos:',
                'offer-on-orders'      => '¡Obtén hasta un 30% de DESCUENTO en tu primer pedido!',
                'remove'               => 'Eliminar',
                'see-details'          => 'Ver Detalles',
                'shopping-cart'        => 'Carrito de Compras',
                'subtotal'             => 'Subtotal',
                'view-cart'            => 'Ver Carrito',
            ],

            'summary' => [
                'cart-summary'              => 'Resumen del Carrito',
                'delivery-charges'          => 'Gastos de Envío',
                'delivery-charges-excl-tax' => 'Gastos de Envío (Excl. Impuestos)',
                'delivery-charges-incl-tax' => 'Gastos de Envío (Incl. Impuestos)',
                'discount-amount'           => 'Monto de Descuento',
                'grand-total'               => 'Total General',
                'place-order'               => 'Realizar Pedido',
                'proceed-to-checkout'       => 'Continuar con el Pago',
                'sub-total'                 => 'Subtotal',
                'sub-total-excl-tax'        => 'Subtotal (Excl. Impuestos)',
                'sub-total-incl-tax'        => 'Subtotal (Incl. Impuestos)',
                'tax'                       => 'Impuesto',

                'estimate-shipping' => [
                    'country'        => 'País',
                    'info'           => 'Ingrese su destino para obtener una estimación de envío e impuestos.',
                    'postcode'       => 'Código Postal',
                    'select-country' => 'Seleccionar País',
                    'select-state'   => 'Seleccionar Estado',
                    'state'          => 'Estado',
                    'title'          => 'Calcular Envío y Impuestos',
                ],
            ],
        ],

        'onepage' => [
            'address' => [
                'add-new'                => 'Agregar nueva dirección',
                'add-new-address'        => 'Agregar nueva dirección',
                'back'                   => 'Volver',
                'billing-address'        => 'Dirección de facturación',
                'check-billing-address'  => 'Falta la dirección de facturación.',
                'check-shipping-address' => 'Falta la dirección de envío.',
                'city'                   => 'Ciudad',
                'company-name'           => 'Nombre de la empresa',
                'confirm'                => 'Confirmar',
                'country'                => 'País',
                'email'                  => 'Correo electrónico',
                'first-name'             => 'Nombre',
                'last-name'              => 'Apellido',
                'postcode'               => 'Código postal',
                'proceed'                => 'Continuar',
                'same-as-billing'        => '¿Usar la misma dirección para el envío?',
                'save'                   => 'Guardar',
                'save-address'           => 'Guardar en la libreta de direcciones',
                'select-country'         => 'Seleccionar país',
                'select-state'           => 'Seleccionar estado',
                'shipping-address'       => 'Dirección de envío',
                'state'                  => 'Estado',
                'street-address'         => 'Dirección',
                'telephone'              => 'Teléfono',
                'title'                  => 'Dirección',
                'vat-id'                 => 'ID de IVA',
            ],

            'index' => [
                'checkout' => 'Realizar Pedido',
                'home'     => 'Inicio',
            ],

            'payment' => [
                'payment-method' => 'Método de Pago',
            ],

            'shipping' => [
                'shipping-method' => 'Método de Envío',
            ],

            'summary' => [
                'cart-summary'              => 'Resumen del Carrito',
                'delivery-charges'          => 'Gastos de Envío',
                'delivery-charges-excl-tax' => 'Gastos de Envío (Excl. Impuestos)',
                'delivery-charges-incl-tax' => 'Gastos de Envío (Incl. Impuestos)',
                'discount-amount'           => 'Monto de Descuento',
                'excl-tax'                  => 'Excl. Impuestos:',
                'grand-total'               => 'Total General',
                'place-order'               => 'Realizar Pedido',
                'price_&_qty'               => ':price × :qty',
                'processing'                => 'Procesando',
                'sub-total'                 => 'Subtotal',
                'sub-total-excl-tax'        => 'Subtotal (Excl. Impuestos)',
                'sub-total-incl-tax'        => 'Subtotal (Incl. Impuestos)',
                'tax'                       => 'Impuesto',
            ],
        ],

        'coupon' => [
            'already-applied' => 'El código de cupón ya ha sido aplicado.',
            'applied'         => 'Cupón aplicado',
            'apply'           => 'Aplicar Cupón',
            'apply-issue'     => 'No se puede aplicar el código de cupón.',
            'button-title'    => 'Aplicar',
            'code'            => 'Código de Cupón',
            'discount'        => 'Descuento de Cupón',
            'enter-your-code' => 'Ingresa tu código',
            'error'           => 'Algo salió mal',
            'invalid'         => 'El código de cupón no es válido.',
            'remove'          => 'Eliminar Cupón',
            'subtotal'        => 'Subtotal',
            'success-apply'   => 'Código de cupón aplicado con éxito.',
        ],

        'login' => [
            'email'    => 'Correo electrónico',
            'password' => 'Contraseña',
            'title'    => 'Iniciar sesión',
        ],
    ],

    'home' => [
        'contact' => [
            'about'         => 'Déjanos una nota y te responderemos lo más rápido posible',
            'desc'          => '¿En qué estás pensando?',
            'describe-here' => 'Describe aquí',
            'email'         => 'Correo electrónico',
            'message'       => 'Mensaje',
            'name'          => 'Nombre',
            'phone-number'  => 'Número de teléfono',
            'submit'        => 'Enviar',
            'title'         => 'Contáctanos',
        ],

        'index' => [
            'categories-carousel' => 'Carrusel de categorías',
            'image-carousel'      => 'Carrusel de imágenes',
            'offer'               => 'Obtén HASTA 40% DE DESCUENTO en tu primer pedido, COMPRA AHORA',
            'product-carousel'    => 'Carrusel de productos',
            'resend-verify-email' => 'Reenviar correo de verificación',
            'verify-email'        => 'Verifica tu cuenta de correo electrónico',
        ],

        'thanks-for-contact' => 'Gracias por contactarnos con tus comentarios y preguntas. Te responderemos muy pronto.',
    ],

    'partials' => [
        'pagination' => [
            'next-page'          => 'Página siguiente',
            'pagination-showing' => 'Mostrando :firstItem a :lastItem de :total entradas',
            'prev-page'          => 'Página anterior',
        ],
    ],

    'errors' => [
        'go-to-home' => 'Ir a inicio',

        '404' => [
            'description' => '¡Vaya! La página que busca está de vacaciones. Parece que no podemos encontrar lo que busca.',
            'title'       => '404 Página no encontrada',
        ],

        '401' => [
            'description' => '¡Vaya! Parece que no tiene permiso para acceder a esta página. Parece que le faltan las credenciales necesarias.',
            'title'       => '401 No autorizado',
        ],

        '403' => [
            'description' => '¡Vaya! Esta página está prohibida. Parece que no tiene los permisos necesarios para ver este contenido.',
            'title'       => '403 Prohibido',
        ],

        '500' => [
            'description' => '¡Vaya! Algo salió mal. Parece que tenemos problemas para cargar la página que busca.',
            'title'       => '500 Error interno del servidor',
        ],

        '503' => [
            'description' => '¡Vaya! Parece que estamos temporalmente fuera de línea por mantenimiento. Por favor, vuelva más tarde.',
            'title'       => '503 Servicio no disponible',
        ],
    ],

    'layouts' => [
        'address'               => 'Dirección',
        'downloadable-products' => 'Productos descargables',
        'gdpr-request'          => 'Solicitudes de GDPR',
        'my-account'            => 'Mi cuenta',
        'orders'                => 'Pedidos',
        'profile'               => 'Perfil',
        'reviews'               => 'Reseñas',
        'wishlist'              => 'Lista de deseos',
    ],

    'subscription' => [
        'already'             => 'Ya está suscrito a nuestro boletín.',
        'subscribe-success'   => 'Se ha suscrito con éxito a nuestro boletín.',
        'unsubscribe-success' => 'Se ha dado de baja con éxito de nuestro boletín.',
    ],

    'emails' => [
        'dear'   => 'Estimado/a :customer_name',
        'thanks' => 'Si necesita ayuda, contáctenos en <a href=":link" style=":style">:email</a>.<br/>¡Gracias!',

        'customers' => [
            'registration' => [
                'credentials-description' => 'Su cuenta ha sido creada. Los detalles de su cuenta se encuentran a continuación:',
                'description'             => 'Su cuenta ha sido creada con éxito y puede iniciar sesión con su dirección de correo electrónico y contraseña. Después de iniciar sesión, podrá acceder a más servicios, incluyendo la revisión de pedidos anteriores, listas de deseos y la edición de su información de cuenta.',
                'greeting'                => '¡Bienvenido y gracias por registrarse con nosotros!',
                'password'                => 'Contraseña',
                'sign-in'                 => 'Iniciar sesión',
                'subject'                 => 'Nuevo registro de cliente',
                'username-email'          => 'Nombre de usuario/Correo electrónico',
            ],

            'forgot-password' => [
                'description'    => 'Recibe este correo electrónico porque hemos recibido una solicitud para restablecer la contraseña de su cuenta.',
                'greeting'       => '¡Contraseña olvidada!',
                'reset-password' => 'Restablecer contraseña',
                'subject'        => 'Correo electrónico para restablecer la contraseña',
            ],

            'update-password' => [
                'description' => 'Recibe este correo electrónico porque ha actualizado su contraseña.',
                'greeting'    => '¡Contraseña actualizada!',
                'subject'     => 'Contraseña actualizada',
            ],

            'verification' => [
                'description'  => 'Por favor, haga clic en el botón de abajo para confirmar su dirección de correo electrónico.',
                'greeting'     => '¡Bienvenido!',
                'subject'      => 'Correo electrónico de verificación de cuenta',
                'verify-email' => 'Confirmar dirección de correo electrónico',
            ],

            'commented' => [
                'description' => 'Nota - :note',
                'subject'     => 'Nuevo comentario añadido',
            ],

            'subscribed' => [
                'description' => '¡Felicidades y bienvenido a nuestra comunidad de boletines! Estamos encantados de tenerte a bordo y mantenerte al día con las últimas noticias, tendencias y ofertas exclusivas.',
                'greeting'    => '¡Bienvenido a nuestro boletín!',
                'subject'     => '¡Usted! Suscríbase a nuestro boletín',
                'unsubscribe' => 'Darse de baja',
            ],

            'gdpr' => [
                'new-delete-request' => 'Nueva solicitud para eliminar datos',
                'new-update-request' => 'Nueva solicitud para actualizar datos',

                'new-request' => [
                    'delete-summary' => 'خلاصه درخواست حذف',
                    'message'        => 'Mensaje : ',
                    'request-status' => 'Estado de la solicitud : ',
                    'request-type'   => 'Tipo de solicitud : ',
                    'update-summary' => 'خلاصه درخواست به‌روزرسانی',
                ],

                'status-update' => [
                    'subject'        => 'Tu solicitud GDPR ha sido actualizada',
                    'summary'        => 'El estado de tu solicitud GDPR ha sido actualizado',
                    'request-status' => 'Estado de la solicitud:',
                    'request-type'   => 'Tipo de solicitud:',
                    'message'        => 'Mensaje:',
                ],
            ],

            'reminder' => [
                'already-paid'    => 'Si ya ha realizado el pago, ignore este mensaje.',
                'invoice-overdue' => 'Este es un recordatorio amable de que su factura está vencida. Le pedimos amablemente que realice el pago lo antes posible.',
                'subject'         => 'Recordatorio de factura',
            ],
        ],

        'contact-us' => [
            'contact-from'    => 'a través del formulario de contacto del sitio web',
            'reply-to-mail'   => 'por favor responda a este correo electrónico.',
            'reach-via-phone' => 'Alternativamente, puede comunicarse con nosotros por teléfono al',
            'inquiry-from'    => 'Consulta de',
            'to'              => 'Para contactar a',
        ],

        'orders' => [
            'created' => [
                'greeting' => 'Gracias por su pedido :order_id, realizado el :created_at',
                'subject'  => 'Nueva confirmación de pedido',
                'summary'  => 'Resumen del pedido',
                'title'    => '¡Confirmación de pedido!',
            ],

            'invoiced' => [
                'greeting' => 'Su factura #:invoice_id para el pedido :order_id, creada el :created_at',
                'subject'  => 'Nueva confirmación de factura',
                'summary'  => 'Resumen de la factura',
                'title'    => '¡Confirmación de factura!',
            ],

            'shipped' => [
                'greeting' => 'Su pedido :order_id, realizado el :created_at, ha sido enviado',
                'subject'  => 'Nueva confirmación de envío',
                'summary'  => 'Resumen del envío',
                'title'    => '¡Pedido enviado!',
            ],

            'refunded' => [
                'greeting' => 'Se ha iniciado el reembolso para el pedido :order_id, realizado el :created_at',
                'subject'  => 'Nueva confirmación de reembolso',
                'summary'  => 'Resumen del reembolso',
                'title'    => '¡Pedido reembolsado!',
            ],

            'canceled' => [
                'greeting' => 'Su pedido :order_id, realizado el :created_at, ha sido cancelado',
                'subject'  => 'Nueva cancelación de pedido',
                'summary'  => 'Resumen del pedido',
                'title'    => '¡Pedido cancelado!',
            ],

            'commented' => [
                'subject' => 'Nuevo comentario agregado',
                'title'   => 'Nuevo comentario agregado a su pedido :order_id, realizado el :created_at',
            ],

            'billing-address'            => 'Dirección de facturación',
            'carrier'                    => 'Transportista',
            'contact'                    => 'Contacto',
            'discount'                   => 'Descuento',
            'excl-tax'                   => 'Excl. Impuestos: ',
            'grand-total'                => 'Total',
            'name'                       => 'Nombre',
            'payment'                    => 'Pago',
            'price'                      => 'Precio',
            'qty'                        => 'Cantidad',
            'shipping'                   => 'Envío',
            'shipping-address'           => 'Dirección de envío',
            'shipping-handling'          => 'Envío y manipulación',
            'shipping-handling-excl-tax' => 'Envío y manipulación (Excl. Impuestos)',
            'shipping-handling-incl-tax' => 'Envío y manipulación (Incl. Impuestos)',
            'sku'                        => 'SKU',
            'subtotal'                   => 'Subtotal',
            'subtotal-excl-tax'          => 'Subtotal (Excl. Impuestos)',
            'subtotal-incl-tax'          => 'Subtotal (Incl. Impuestos)',
            'tax'                        => 'Impuestos',
            'tracking-number'            => 'Número de seguimiento: :tracking_number',
        ],
    ],
];
