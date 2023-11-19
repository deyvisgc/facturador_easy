<?php

use Modules\LevelAccess\Models\ModuleLevel;
use Illuminate\Database\Migrations\Migration;

class AddLevelsToModuleLevelsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$data = [
			// $pos = [
			['id' => 15, 'module_id' => 6, 'value' => 'pos', 'description' => 'Punto de venta'],
			['id' => 16, 'module_id' => 6, 'value' => 'cash', 'description' => 'Caja chica POS'],
			// $products = [
			['id' => 17, 'module_id' => 17, 'value' => 'items', 'description' => 'Productos'],
			['id' => 18, 'module_id' => 17, 'value' => 'items_packs', 'description' => 'Packs'],
			['id' => 19, 'module_id' => 17, 'value' => 'items_services', 'description' => 'Servicios'],
			['id' => 20, 'module_id' => 17, 'value' => 'items_categories', 'description' => 'Categorías'],
			['id' => 21, 'module_id' => 17, 'value' => 'items_brands', 'description' => 'Marcas'],
			['id' => 22, 'module_id' => 17, 'value' => 'items_lots', 'description' => 'Series'],
			// $clients = [
			['id' => 23, 'module_id' => 18, 'value' => 'clients', 'description' => 'Clientes'],
			['id' => 24, 'module_id' => 18, 'value' => 'clients_types', 'description' => 'Tipos de clientes'],
			// $purchases = [
			['id' => 25, 'module_id' => 2, 'value' => 'purchases_create', 'description' => 'Nuevo'],
			['id' => 26, 'module_id' => 2, 'value' => 'purchases_list', 'description' => 'Listado'],
			['id' => 27, 'module_id' => 2, 'value' => 'purchases_orders', 'description' => 'Ordenes de compra'],
			['id' => 28, 'module_id' => 2, 'value' => 'purchases_expenses', 'description' => 'Gastos diversos'],
			['id' => 29, 'module_id' => 2, 'value' => 'purchases_suppliers', 'description' => 'Proveedores'],
			['id' => 30, 'module_id' => 2, 'value' => 'purchases_quotations', 'description' => 'Solicitar cotización'],
			['id' => 31, 'module_id' => 2, 'value' => 'purchases_fixed_assets_items', 'description' => 'Activos fijos - Ítems'],
			['id' => 32, 'module_id' => 2, 'value' => 'purchases_fixed_assets_purchases', 'description' => 'Activos fijos - Compras'],
			// $inventories = [
			['id' => 33, 'module_id' => 8, 'value' => 'inventory', 'description' => 'Movimientos'],
			['id' => 34, 'module_id' => 8, 'value' => 'inventory_transfers', 'description' => 'Traslados'],
			['id' => 35, 'module_id' => 8, 'value' => 'inventory_devolutions', 'description' => 'Devoluciones'],
			['id' => 36, 'module_id' => 8, 'value' => 'inventory_report_kardex', 'description' => 'Reporte kardex'],
			['id' => 37, 'module_id' => 8, 'value' => 'inventory_report', 'description' => 'Reporte inventario'],
			['id' => 38, 'module_id' => 8, 'value' => 'inventory_report_kardex', 'description' => 'Kardex valorizado'],
			// $users = [
			['id' => 39, 'module_id' => 14, 'value' => 'users', 'description' => 'Usuarios'],
			['id' => 40, 'module_id' => 14, 'value' => 'users_establishments', 'description' => 'Establecimientos'],
			// $advanced = [
			['id' => 41, 'module_id' => 3, 'value' => 'advanced_retentions', 'description' => 'Retenciones'],
			['id' => 42, 'module_id' => 3, 'value' => 'advanced_dispatches', 'description' => 'Guías de remisión'],
			['id' => 43, 'module_id' => 3, 'value' => 'advanced_perceptions', 'description' => 'Percepciones'],
			['id' => 44, 'module_id' => 3, 'value' => 'advanced_order_forms', 'description' => 'Ordenes de pedido'],
			// $account = [
			['id' => 45, 'module_id' => 9, 'value' => 'account_report', 'description' => 'Exportar reporte'],
			['id' => 46, 'module_id' => 9, 'value' => 'account_formats', 'description' => 'Exportar formatos'],
			['id' => 47, 'module_id' => 9, 'value' => 'account_summary', 'description' => 'Reporte resumido - Ventas'],
			// $finances = [
			['id' => 48, 'module_id' => 12, 'value' => 'finances_movements', 'description' => 'Movimientos'],
			['id' => 49, 'module_id' => 12, 'value' => 'finances_incomes', 'description' => 'Ingresos'],
			['id' => 50, 'module_id' => 12, 'value' => 'finances_unpaid', 'description' => 'Cuentas por cobrar'],
			['id' => 51, 'module_id' => 12, 'value' => 'finances_to_pay', 'description' => 'Cuentas por pagar'],
			['id' => 52, 'module_id' => 12, 'value' => 'finances_payments', 'description' => 'Pagos'],
			['id' => 53, 'module_id' => 12, 'value' => 'finances_balance', 'description' => 'Balance'],
			['id' => 54, 'module_id' => 12, 'value' => 'finances_payment_method_types', 'description' => 'Ingresos y Egresos - M. Pago'],
			// $account_users = [
			['id' => 55, 'module_id' => 5, 'value' => 'account_users_settings', 'description' => 'Configuración'],
			['id' => 56, 'module_id' => 5, 'value' => 'account_users_list', 'description' => 'Lista de pagos'],
			// $hotels = [
			['id' => 57, 'module_id' => 15, 'value' => 'hotels_reception', 'description' => 'Recepción'],
			['id' => 58, 'module_id' => 15, 'value' => 'hotels_rates', 'description' => 'Tarifas'],
			['id' => 59, 'module_id' => 15, 'value' => 'hotels_floors', 'description' => 'Pisos'],
			['id' => 60, 'module_id' => 15, 'value' => 'hotels_cats', 'description' => 'Categorías'],
			['id' => 61, 'module_id' => 15, 'value' => 'hotels_rooms', 'description' => 'Habitaciones'],
			// $documentary
			['id' => 62, 'module_id' => 16, 'value' => 'documentary_offices', 'description' => 'Oficinas'],
			['id' => 63, 'module_id' => 16, 'value' => 'documentary_process', 'description' => 'Procesos'],
			['id' => 64, 'module_id' => 16, 'value' => 'documentary_documents', 'description' => 'Tipos de documento'],
			['id' => 65, 'module_id' => 16, 'value' => 'documentary_actions', 'description' => 'Acciones'],
			['id' => 66, 'module_id' => 16, 'value' => 'documentary_files', 'description' => 'Expedientes'],
		];

		ModuleLevel::query()->insert($data);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		ModuleLevel::whereIn('module_id', [6, 10, 17, 18, 2, 8, 14, 3, 9, 12, 11, 15, 16])
			->delete();
	}
}
