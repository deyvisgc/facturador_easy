<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ titleTopBar }}</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <template v-if="typeUser === 'admin'">
                    <div class="btn-group flex-wrap">
                        <button
                            type="button"
                            class="btn btn-custom btn-sm mt-2 mr-2 dropdown-toggle"
                            data-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i class="fa fa-download"></i> Exportar
                            <span class="caret"></span>
                        </button>
                        <div
                            class="dropdown-menu"
                            role="menu"
                            x-placement="bottom-start"
                            style="
                                position: absolute;
                                will-change: transform;
                                top: 0px;
                                left: 0px;
                                transform: translate3d(0px, 42px, 0px);
                            "
                        >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickExport()"
                                >Listado</a
                            >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickExportWp()"
                                >Woocommerce</a
                            >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickExportBarcode()"
                                >Etiquetas</a
                            >
                        </div>
                    </div>
                    <div class="btn-group flex-wrap">
                        <button
                            type="button"
                            class="btn btn-custom btn-sm mt-2 mr-2 dropdown-toggle"
                            data-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i class="fa fa-upload"></i> Importar
                            <span class="caret"></span>
                        </button>
                        <div
                            class="dropdown-menu"
                            role="menu"
                            x-placement="bottom-start"
                            style="
                                position: absolute;
                                will-change: transform;
                                top: 0px;
                                left: 0px;
                                transform: translate3d(0px, 42px, 0px);
                            "
                        >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickImport()"
                                >Productos</a
                            >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickImportListPrice()"
                                >L. Precios</a
                            >
                        </div>
                    </div>
                </template>
                <button
                    type="button"
                    class="btn btn-custom btn-sm mt-2 mr-2"
                    @click.prevent="clickCreate()"
                    v-if="can_add_new_product"
                >
                    <i class="fa fa-plus-circle"></i> Nuevo
                </button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">{{ title }}</h3>
            </div>
            <div class="data-table-visible-columns">
                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(column, index) in columnsComputed" :key="index">
                            <el-checkbox
                                v-if="column.title !== undefined && column.visible !== undefined"
                                v-model="column.visible"
                            >{{ column.title }}</el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            <div class="card-body">
                <data-table :resource="resource" :productType="type">
                    <tr slot="heading" width="100%">
                        <th>#</th>
                        <th>Cód. Interno</th>
                        <th>Unidad</th>
                        <th>Nombre</th>
                        <th v-if="columns.description.visible">Descripción</th>
                        <th v-if="columns.model.visible">Modelo</th>
                        <th v-if="columns.brand.visible">Marca</th>
                        <th v-if="columns.item_code.visible">Cód. SUNAT</th>
                        <th v-if="(columns.sanitary!== undefined && columns.sanitary.visible===true )">R.S.</th>
                        <th v-if="(columns.cod_digemid!== undefined && columns.cod_digemid.visible===true )">DIGEMID</th>
                        <template v-if="typeUser == 'admin'">
                            <th class="text-center">Historial</th>
                        </template>
                        <th class="text-left">Stock</th>
                        <th v-if="(columns.extra_data!== undefined && columns.extra_data.visible===true )" class="text-center">Stock por datos extra</th>
                        <th class="text-right">P.Unitario (Venta)</th>
                        <th v-if="typeUser != 'seller' && columns.purchase_unit_price.visible" class="text-right">
                            P.Unitario (Compra)
                        </th>
                        <th class="text-center">Tiene Igv (Venta)</th>
                        <th class="text-center" v-if="columns.purchase_has_igv_description.visible">Tiene Igv (Compra)</th>
                        <th class="text-right"></th>
                    </tr>

                    <tr></tr>
                    <tr
                        slot-scope="{ index, row }"
                        :class="{ disable_color: !row.active }"
                    >
                        <td>{{ index }}</td>
                        <td>{{ row.internal_id }}</td>
                        <td>{{ row.unit_type_id }}</td>
                        <td>{{ row.description }}</td>
                        <td v-if="columns.model.visible">{{ row.model }}</td>
                        <td v-if="columns.brand.visible">{{ row.brand }}</td>
                        <td v-if="columns.description.visible">{{ row.name }}</td>
                        <td v-if="columns.item_code.visible">{{ row.item_code }}</td>
                        <td v-if="(columns.sanitary!== undefined && columns.sanitary.visible===true )">{{ row.sanitary }}</td>
                        <td v-if="(columns.cod_digemid!== undefined && columns.cod_digemid.visible===true )">{{ row.cod_digemid }}</td>

                        <template v-if="typeUser == 'admin'">
                            <td class="text-center">
                                <button
                                    type="button"
                                    class="btn waves-effect waves-light btn-xs btn-primary"
                                    @click.prevent="clickHistory(row.id)"
                                >
                                    <i class="fa fa-history"></i>
                                </button>
                            </td>
                        </template>

                        <td>
                            <div v-if="config.product_only_location == true">
                                {{ row.stock }}
                            </div>
                            <div v-else>
                                <template
                                    v-if="
                                        typeUser == 'seller' &&
                                        row.unit_type_id != 'ZZ'
                                    "
                                    >{{ row.stock }}</template
                                >
                                <template
                                    v-else-if="
                                        typeUser != 'seller' &&
                                        row.unit_type_id != 'ZZ'
                                    "
                                >
                                    <button
                                        type="button"
                                        class="btn waves-effect waves-light btn-xs btn-info"
                                        @click.prevent="
                                            clickWarehouseDetail(row.warehouses, row.item_unit_types)
                                        "
                                    >
                                        <i class="fa fa-search"></i>
                                    </button>
                                </template>
                            </div>
                            <!-- <template v-for="item in row.warehouses">
                                <template>{{item.stock}} - {{item.warehouse_description}}</template><br>
                            </template> -->

                            <!-- <br/>Mín:{{ row.stock_min }} -->
                        </td>
                        <td v-if="(columns.extra_data!== undefined && columns.extra_data.visible===true )"
                            class="text-center">

                            <template v-if="
                            config.show_extra_info_to_item &&
                            (
                                row.stock_by_extra.total !== null ||
                                row.stock_by_extra.colors !== null ||
                                row.stock_by_extra.CatItemSize !== null ||
                                row.stock_by_extra.CatItemStatus !== null ||
                                row.stock_by_extra.CatItemUnitBusiness !== null ||
                                row.stock_by_extra.CatItemMoldCavity !== null ||
                                row.stock_by_extra.CatItemPackageMeasurement !== null ||
                                row.stock_by_extra.CatItemUnitsPerPackage !== null ||
                                row.stock_by_extra.CatItemMoldProperty !== null ||
                                row.stock_by_extra.CatItemProductFamily !== null
                            )
                                ">
                                <button
                                    type="button"
                                    class="btn waves-effect waves-light btn-xs btn-primary"
                                    @click.prevent="clickStockItems(row)"
                                >
                                    <i class="fa fa-database"></i>
                                </button>
                            </template>
                        </td>
                        <td class="text-right">{{ row.sale_unit_price }}</td>
                        <td v-if="typeUser != 'seller' && columns.purchase_unit_price.visible" class="text-right">
                            {{ row.purchase_unit_price }}
                        </td>
                        <td class="text-center">
                            {{ row.has_igv_description }}
                        </td>
                        <td class="text-center" v-if="columns.purchase_has_igv_description.visible">
                            {{ row.purchase_has_igv_description }}
                        </td>
                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                    <template v-if="typeUser === 'admin'">
                                        <button
                                            class="dropdown-item"
                                            @click.prevent="clickCreate(row.id)"
                                        >
                                            Editar
                                        </button>
                                        <button
                                            class="dropdown-item"
                                            @click.prevent="clickDelete(row.id)"
                                        >
                                            Eliminar
                                        </button>
                                        <button
                                            class="dropdown-item"
                                            @click.prevent="duplicate(row.id)"
                                        >
                                            Duplicar
                                        </button>

                                        <button
                                            class="dropdown-item"
                                            @click.prevent="clickDisable(row.id)"
                                            v-if="row.active"
                                        >
                                            Inhabilitar
                                        </button>
                                        <button
                                            class="dropdown-item"
                                            @click.prevent="clickEnable(row.id)"
                                            v-else
                                        >
                                            Habilitar
                                        </button>

                                        <button
                                            class="dropdown-item"
                                            @click.prevent="clickBarcode(row)"
                                        >
                                            Cod. Barras
                                        </button>

                                        <button
                                            class="dropdown-item"
                                            @click.prevent="clickPrintBarcode(row)"
                                        >
                                            Etiquetas
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </td>
                    </tr>
                </data-table>
            </div>

            <items-form
                :showDialog.sync="showDialog"
                :recordId="recordId"
                :type="type"
            ></items-form>

            <items-import :showDialog.sync="showImportDialog"></items-import>
            <items-export :showDialog.sync="showExportDialog"></items-export>
            <items-export-wp
                :showDialog.sync="showExportWpDialog"
            ></items-export-wp>
            <items-export-barcode
                :showDialog.sync="showExportBarcodeDialog"
            ></items-export-barcode>

            <warehouses-detail
                :showDialog.sync="showWarehousesDetail"
                :warehouses="warehousesDetail"
                :item_unit_types="item_unit_types"
            >
            </warehouses-detail>

            <items-import-list-price
                :showDialog.sync="showImportListPriceDialog"
            ></items-import-list-price>
            <tenant-item-aditional-info-modal
                :showDialog.sync="showDialogItemStock"
                :item="recordItem"
            ></tenant-item-aditional-info-modal>
            <items-history
                :showDialog.sync="showDialogHistory"
                :recordId="recordId"
            >
            </items-history>
        </div>
    </div>
</template>
<script>

import ItemsForm from "./form.vue";
import WarehousesDetail from "./partials/warehouses.vue";
import ItemsImport from "./import.vue";
import ItemsImportListPrice from "./partials/import_list_price.vue";
import ItemsExport from "./partials/export.vue";
import ItemsExportWp from "./partials/export_wp.vue";
import ItemsExportBarcode from "./partials/export_barcode.vue";
import DataTable from "../../../components/DataTable.vue";
import {deletable} from "../../../mixins/deletable";
import ItemsHistory from "@viewsModuleItem/items/history.vue";
import {mapActions, mapState} from "vuex";

export default {
    props: [
        "configuration",
        "typeUser",
        "type"],
    mixins: [deletable],
    components: {
        ItemsForm,
        ItemsImport,
        ItemsExport,
        ItemsExportWp,
        ItemsExportBarcode,
        DataTable,
        WarehousesDetail,
        ItemsImportListPrice,
        ItemsHistory,
    },
    data() {
        return {
            can_add_new_product: false,
            showDialog: false,
            showImportDialog: false,
            showExportDialog: false,
            showExportWpDialog: false,
            showExportBarcodeDialog: false,
            showImportListPriceDialog: false,
            showWarehousesDetail: false,
            resource: "items",
            recordId: null,
            recordItem: {},
            warehousesDetail: [],
            columns: {
                description: {
                    title: 'Descripción',
                    visible: false
                },
                item_code: {
                    title: 'Cód. SUNAT',
                    visible: false
                },
                purchase_unit_price: {
                    title: 'P.Unitario (Compra)',
                    visible: false
                },
                purchase_has_igv_description: {
                    title: 'Tiene Igv (Compra)',
                    visible: false
                },
                model: {
                    title: 'Modelo',
                    visible: false
                },
                brand: {
                    title: 'Marca',
                    visible: false
                },
                sanitary: {
                    title: 'N° Sanitario',
                    visible: false
                },
                cod_digemid: {
                    title: 'DIGEMID',
                    visible: false
                },
                extra_data: {
                    title: 'Stock Por datos extra',
                    visible: false
                },
            },
            item_unit_types: [],
            titleTopBar: '',
            title: '',
            showDialogHistory: false,
            showDialogItemStock: false,
        };
    },
    created() {
        this.$store.commit('setConfiguration', this.configuration);
        this.loadConfiguration()

        if(this.config.is_pharmacy !== true){
            delete this.columns.sanitary;
            delete this.columns.cod_digemid;
         }
         if(this.config.show_extra_info_to_item !== true){
             delete this.columns.extra_data;

         }
        if (this.type === 'ZZ') {
            this.titleTopBar = 'Servicios';
            this.title = 'Listado de servicios';
        } else {
            this.titleTopBar = 'Productos';
            this.title = 'Listado de productos';
        }
        this.$http.get(`/configurations/record`).then((response) => {
            this.$store.commit('setConfiguration',response.data.data);
            //this.config = response.data.data;
        });
        this.canCreateProduct();
    },
    computed:{
        ...mapState([
            'config',
        ]),
        columnsComputed:function(){
            return this.columns;
        }
    },
    methods: {

        ...mapActions([
            'loadConfiguration',
        ]),
        clickHistory(recordId){
            this.recordId = recordId
            this.showDialogHistory = true
        },
        clickStockItems(row){
            this.recordItem = row
            this.showDialogItemStock = true
        },
        canCreateProduct()
        {
            if (this.typeUser === 'admin') {
                this.can_add_new_product = true
            } else if (this.typeUser === 'seller') {
                if (this.config !== undefined && this.config.seller_can_create_product !== undefined) {
                    this.can_add_new_product = this.config.seller_can_create_product;
                }
            }
            return this.can_add_new_product;
        },
        duplicate(id) {
            this.$http
                .post(`${this.resource}/duplicate`, { id })
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(
                            "Se guardaron los cambios correctamente."
                        );
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error("No se guardaron los cambios");
                    }
                })
                .catch((error) => {});
            this.$eventHub.$emit("reloadData");
        },
        clickWarehouseDetail(warehouses, item_unit_types) {
            this.warehousesDetail = warehouses;
            this.item_unit_types = item_unit_types
            this.showWarehousesDetail = true;
        },
        clickCreate(recordId = null) {
            this.recordId = recordId;
            this.showDialog = true;
        },
        clickImport() {
            this.showImportDialog = true;
        },
        clickExport() {
            this.showExportDialog = true;
        },
        clickExportWp() {
            this.showExportWpDialog = true;
        },
        clickExportBarcode() {
            this.showExportBarcodeDialog = true;
        },
        clickImportListPrice() {
            this.showImportListPriceDialog = true;
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickDisable(id) {
            this.disable(`/${this.resource}/disable/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickEnable(id) {
            this.enable(`/${this.resource}/enable/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickBarcode(row) {
            if (!row.barcode) {
                return this.$message.error(
                    "Para generar el código de barras debe registrar el código de barras."
                );
            }

            window.open(`/${this.resource}/barcode/${row.id}`);
        },
        clickPrintBarcode(row) {
            if (!row.barcode) {
                return this.$message.error(
                    "Para generar el código de barras debe registrar el código de barras."
                );
            }

            window.open(`/${this.resource}/export/barcode/print?id=${row.id}`);
        },
    },
};
</script>
