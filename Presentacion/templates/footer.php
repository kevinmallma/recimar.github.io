                        <footer class="text-center mb-n3">
                            <p class="text-muted">Derechos Reservados &copy; 2020-2023</p>
                        </footer>
                        <!--footer-->
                    </div>
                </main> 
                <!-- contenido -->
            </div>
        </div>
        <!-- menu pc-cuerpo -->

        <!-- Bootstrap JS -->
        <!-- jquery -->
        <script src="lib/jquery/jquery-3.5.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-ui/js/jquery-ui.min.js"></script>
        <!-- datepicker -->
        <script src="lib/bootstrap-4.6.0-dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>
        <!--Datatable-->
        <script src="lib/datatables/DataTables-1.10.22/js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="lib/datatables/DataTables-1.10.22/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
        <script src="lib/datatables/Responsive-2.2.6/js/dataTables.responsive.min.js" type="text/javascript"></script>
        <script src="lib/datatables/Responsive-2.2.6/js/responsive.bootstrap4.min.js" type="text/javascript"></script>
        <!-- alert -->
        <script src="lib/alert/sweetalert2.all.min.js" type="text/javascript"></script>
        <!-- iconos -->
        <script src="js/all.min.js" type="text/javascript"></script>
        <!-- select2 -->
        <script src="lib/select2/js/select2.min.js" type="text/javascript"></script>
        <script src="lib/select2/js/i18n/es.js" type="text/javascript"></script>
        <script src="lib/Highcharts-9.1.0/highcharts.js"></script>
        <script src="lib/Highcharts-9.1.0/highcharts-3d.js"></script>
        <script src="lib/Highcharts-9.1.0/exporting.js"></script>
        <script src="lib/Highcharts-9.1.0/export-data.js"></script>
        <script src="lib/Highcharts-9.1.0/accessibility.js"></script>
        <!-- js propios -->
        <script src="js/app.js" type="text/javascript"></script>
        <?php 
            switch ($pagina) {
                case 'Inicio':
                    echo '<script src="js/inicio.js" type="text/javascript"></script>';
                    break;
                case 'Usuarios':
                    echo '<script src="js/usuarios.js" type="text/javascript"></script>';
                    break;
                case 'Clientes':
                    echo '<script src="js/clientes.js" type="text/javascript"></script>';
                    break;
                case 'Materiales':
                    echo '<script src="js/materiales.js" type="text/javascript"></script>';
                    break;
                 case 'Nueva Compra':
                    echo '<script src="js/compra_nueva.js" type="text/javascript"></script>';
                    break;
                case 'Buscar Compra':
                    echo '<script src="js/compra_buscar.js" type="text/javascript"></script>';
                    break;
                case 'Reporte Compra':
                    echo '<script src="js/compra_reporte.js" type="text/javascript"></script>';
                    break;
                case 'Gra compra':
                    echo '<script src="js/app_grafico.js" type="text/javascript"></script>';
                    echo '<script src="js/gra_compras.js" type="text/javascript"></script>';
                    break;
                case 'Gra material':
                    echo '<script src="js/app_grafico.js" type="text/javascript"></script>';
                    echo '<script src="js/gra_material.js" type="text/javascript"></script>';
                    break;
            }
        ?>
    </body>
</html>