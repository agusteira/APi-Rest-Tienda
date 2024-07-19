# Tienda de Ropa - API REST

Este proyecto es una API REST desarrollada en PHP utilizando el framework Slim, para la gestión de una tienda de ropa. Permite realizar operaciones CRUD sobre los productos de la tienda, gestionar ventas, y administrar usuarios.

Características
Rutas de la aplicación
1. Gestión de Productos
POST /tienda/alta

Recibe: Nombre, Precio, Tipo ("Camiseta" o "Pantalón"), Talla (“S”, “M”, “L”), Color, Stock (unidades), e imagen del producto.
Descripción: Guarda los datos del producto en la base de datos. Si el nombre y tipo ya existen, se actualiza el precio y se suma al stock existente. La imagen se guarda en la carpeta /ImagenesDeRopa/2024.
POST /tienda/consultar

Recibe: Nombre, Tipo, Color.
Descripción: Consulta si existe un producto con los datos proporcionados. Retorna "existe" si hay coincidencia, de lo contrario informa si no existe el nombre o el tipo.
2. Gestión de Ventas
POST /ventas/alta

Recibe: Email del usuario, Nombre, Tipo, Talla, Stock, e imagen del usuario.
Descripción: Registra una venta si el producto existe y hay stock disponible. Descuenta la cantidad vendida del stock y guarda la venta en la base de datos junto con la imagen en la carpeta /ImagenesDeVenta/2024.
GET /ventas/consultar

/productos/vendidos: Consulta la cantidad de productos vendidos en un día específico. Si no se pasa fecha, muestra los del día anterior.
/ventas/porUsuario: Lista las ventas de un usuario específico.
/ventas/porProducto: Lista las ventas por tipo de producto.
/productos/entreValores: Lista productos cuyo precio está entre dos valores ingresados.
/ventas/ingresos: Lista los ingresos por ventas en una fecha específica. Si no se ingresa fecha, muestra los ingresos de todos los días.
/productos/masVendido: Muestra el producto más vendido.
PUT /ventas/modificar

Recibe: Número de pedido, Email del usuario, Nombre, Tipo, Talla, Cantidad.
Descripción: Modifica una venta existente. Si no existe el número de pedido, informa que no existe.
3. Gestión de Usuarios
POST /registro

Recibe: Mail, Usuario, Contraseña, Perfil, e imagen del usuario.
Descripción: Registra un nuevo usuario y guarda la imagen en la carpeta ImagenesDeUsuarios/2024/.
POST /login

Recibe: Usuario, Contraseña.
Descripción: Realiza el login del usuario y devuelve un token JWT que verifica al usuario junto a su perfil.
4. Middleware
ConfirmarPerfil

Descripción: Middleware que confirma que el perfil del token JWT es el correcto. Limita el acceso a las rutas según el perfil del usuario.
Middlewares para /tienda/consultar

Descripción: Verifica que los datos necesarios para realizar las consultas estén presentes.
5. Descarga de Ventas
GET /ventas/descargar
Descripción: Permite descargar un CSV del listado de ventas (solo para administradores).
