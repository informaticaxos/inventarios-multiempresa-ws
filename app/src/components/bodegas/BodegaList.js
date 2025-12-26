
import React, { useState, useEffect } from 'react';
import { Box, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper, Button, Typography, Toolbar, Container, Dialog, DialogTitle, DialogContent, DialogActions, TextField, InputAdornment, Autocomplete } from '@mui/material';
import api from '../../services/api';
import Header from '../common/Header';
import Swal from 'sweetalert2';
import { GoogleMap, Marker, useJsApiLoader } from '@react-google-maps/api';

const GOOGLE_MAPS_API_KEY = process.env.REACT_APP_GOOGLE_MAPS_API_KEY || '';
const MAP_CONTAINER_STYLE = { width: '100%', height: '300px', marginTop: 8, marginBottom: 8 };
const DEFAULT_CENTER = { lat: -2.90055, lng: -78.99908 };

const BodegaList = () => {
  const [bodegas, setBodegas] = useState([]);
  const [companies, setCompanies] = useState([]);
  const [error, setError] = useState('');
  const [page, setPage] = useState(1);
  const [limit] = useState(10);
  const [totalPages, setTotalPages] = useState(1);
  const [total, setTotal] = useState(0);
  const [openForm, setOpenForm] = useState(false);
  const [editBodega, setEditBodega] = useState(null);
  const [form, setForm] = useState({
    code: '',
    name: '',
    address: '',
    location: '',
    company_id: '',
    company_name: ''
  });
  // Estado para el marcador del mapa
  const [marker, setMarker] = useState(null);
  // Cargar Google Maps solo si hay API Key
  const { isLoaded } = useJsApiLoader({
    googleMapsApiKey: GOOGLE_MAPS_API_KEY,
    libraries: ['places'],
  });
  const [search, setSearch] = useState('');
  const [empresaFiltro, setEmpresaFiltro] = useState(null);

  useEffect(() => {
    fetchBodegas(page);
    fetchCompanies();
  }, [page]);

  const fetchBodegas = async (pageNum = 1) => {
    try {
      // Usar el endpoint y estructura de respuesta exacta proporcionada
      const response = await api.get('/stores');
      if (response.data && response.data.state === 1 && Array.isArray(response.data.data)) {
        setBodegas(response.data.data);
        setTotalPages(1);
        setTotal(response.data.data.length);
        setError('');
      } else {
        setBodegas([]);
        setTotalPages(1);
        setTotal(0);
        setError(response.data?.message || 'No se encontraron bodegas.');
      }
    } catch (err) {
      setBodegas([]);
      setTotalPages(1);
      setTotal(0);
      setError('Error al obtener bodegas');
    }
  };

  const fetchCompanies = async () => {
    try {
      const response = await api.get('/companies');
      if (response.data && response.data.state === 1 && Array.isArray(response.data.data)) {
        setCompanies(response.data.data);
      } else {
        setCompanies([]);
      }
    } catch (err) {
      setCompanies([]);
    }
  };

  const handleFormChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleCompanyChange = (event, value) => {
    setForm({ ...form, company_id: value ? value.id_company : '', company_name: value ? value.name_company : '' });
  };

  const handleEdit = (bodega) => {
    setEditBodega(bodega);
    setForm({
      code: bodega.code || bodega.code_store || '',
      name: bodega.name || bodega.name_store || '',
      address: bodega.address || bodega.address_store || '',
      location: bodega.location || bodega.location_store || '',
      company_id: bodega.company_id || bodega.fk_id_company || '',
      company_name: bodega.company_name || '',
    });
    // Si hay location, setear el marcador
    if (bodega.location_store) {
      const match = bodega.location_store.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/) || bodega.location_store.match(/q=(-?\d+\.\d+),(-?\d+\.\d+)/);
      if (match) {
        setMarker({ lat: parseFloat(match[1]), lng: parseFloat(match[2]) });
      } else {
        setMarker(null);
      }
    } else {
      setMarker(null);
    }
    setOpenForm(true);
  };
  // Cuando cambia el marcador, actualizar el campo location con la URL de Google Maps
  const handleMapClick = (e) => {
    const lat = e.latLng.lat();
    const lng = e.latLng.lng();
    setMarker({ lat, lng });
    setForm({ ...form, location: `https://maps.google.com/?q=${lat},${lng}` });
  };

  const handleDelete = async (bodega) => {
    const result = await Swal.fire({
      title: '¿Eliminar bodega?',
      text: `¿Seguro que deseas eliminar la bodega ${bodega.name}?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    });
    if (result.isConfirmed) {
      try {
        const response = await api.delete(`/stores/${bodega.id}`);
        if (response.data.state) {
          Swal.fire('Eliminado', 'Bodega eliminada correctamente', 'success');
          fetchBodegas(page);
        } else {
          Swal.fire('Error', response.data.message, 'error');
        }
      } catch (err) {
        Swal.fire('Error', 'No se pudo eliminar la bodega', 'error');
      }
    }
  };

  const handleCreateOrUpdateBodega = async () => {
    if (!form.name.trim() || !form.company_id || !form.code.trim()) {
      Swal.fire({
        icon: 'warning',
        title: 'Campos requeridos',
        text: 'Los campos Código, Nombre y Empresa son obligatorios.',
      });
      return;
    }
    try {
      let response;
      const payload = {
        code_store: form.code,
        name_store: form.name,
        address_store: form.address,
        location_store: form.location,
        fk_id_company: form.company_id
      };
      if (editBodega) {
        response = await api.put(`/stores/${editBodega.id || editBodega.id_store}`, payload);
      } else {
        response = await api.post('/stores', payload);
      }
      if (response.data && response.data.state) {
        setOpenForm(false);
        setEditBodega(null);
        setForm({ code: '', name: '', address: '', location: '', company_id: '', company_name: '' });
        setPage(1);
        fetchBodegas(1);
        Swal.fire({
          icon: 'success',
          title: 'Éxito',
          text: editBodega ? 'Bodega actualizada correctamente' : 'Bodega creada correctamente',
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: (response.data && response.data.message) ? response.data.message : 'No se pudo guardar la bodega. Verifica los datos.',
        });
      }
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: err.response?.data?.message || err.message || (editBodega ? 'No se pudo actualizar la bodega' : 'No se pudo crear la bodega'),
      });
    }
  };

  // Filtro de búsqueda y empresa frontend
  const filteredBodegas = bodegas.filter((bodega) => {
    const searchLower = search.toLowerCase();
    const name = bodega.name || bodega.name_store || '';
    const companyName = bodega.company_name || (companies.find(c => c.id_company === (bodega.company_id || bodega.fk_id_company))?.name_company) || '';
    const matchSearch = !search || name.toLowerCase().includes(searchLower) || companyName.toLowerCase().includes(searchLower);
    const matchEmpresa = !empresaFiltro || (bodega.company_id || bodega.fk_id_company) === empresaFiltro.id_company;
    return matchSearch && matchEmpresa;
  });

  return (
    <Box sx={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
      <Header />
      <Box sx={{ flexGrow: 1, p: 3, overflow: 'auto' }}>
        <Toolbar />
        <Container maxWidth="lg">
          <Typography variant="h4" gutterBottom>
            Bodegas
          </Typography>
          <Box sx={{ display: 'flex', alignItems: 'center', mb: 2, gap: 2, flexWrap: 'wrap' }}>
            <Button variant="contained" color="primary" onClick={() => { setOpenForm(true); setEditBodega(null); setForm({ name: '', company_id: '', company_name: '' }); }}>
              Agregar Bodega
            </Button>
            <TextField
              label="Buscar por nombre o empresa"
              variant="outlined"
              size="small"
              value={search}
              onChange={e => setSearch(e.target.value)}
              sx={{ minWidth: 250 }}
              InputProps={{
                startAdornment: <InputAdornment position="start">🔍</InputAdornment>,
              }}
            />
            <Autocomplete
              options={companies}
              getOptionLabel={(option) => option.name_company || ''}
              value={empresaFiltro}
              onChange={(_, value) => setEmpresaFiltro(value)}
              renderInput={(params) => (
                <TextField {...params} label="Filtrar por empresa" variant="outlined" size="small" sx={{ minWidth: 220 }} />
              )}
              isOptionEqualToValue={(option, value) => option.id_company === value?.id_company}
              clearOnEscape
            />
            {empresaFiltro && (
              <Button size="small" onClick={() => setEmpresaFiltro(null)} color="secondary">Limpiar filtro</Button>
            )}
          </Box>
          <TableContainer component={Paper} sx={{ overflowX: 'auto', boxShadow: 3, borderRadius: 2, mb: 2 }}>
            <Table>
              <TableHead>
                <TableRow>
                  <TableCell align="center" sx={{ fontWeight: 'bold', width: 80 }}>ID</TableCell>
                  <TableCell align="center" sx={{ fontWeight: 'bold', width: 120 }}>Código</TableCell>
                  <TableCell align="center" sx={{ fontWeight: 'bold', width: 180 }}>Nombre</TableCell>
                  <TableCell align="center" sx={{ fontWeight: 'bold', width: 220 }}>Dirección</TableCell>
                  <TableCell align="center" sx={{ fontWeight: 'bold', width: 180 }}>Ubicación</TableCell>
                  <TableCell align="center" sx={{ fontWeight: 'bold', width: 220 }}>Empresa</TableCell>
                  <TableCell align="center" sx={{ fontWeight: 'bold', width: 180 }}>Acciones</TableCell>
                </TableRow>
              </TableHead>
              <TableBody>
                {filteredBodegas.length === 0 ? (
                  <TableRow>
                    <TableCell colSpan={7} align="center">No hay bodegas para mostrar.</TableCell>
                  </TableRow>
                ) : (
                  filteredBodegas.map((bodega) => {
                    // Buscar el nombre de la empresa por fk_id_company
                    const empresa = companies.find(c => c.id_company === bodega.fk_id_company);
                    return (
                      <TableRow key={bodega.id_store} hover>
                        <TableCell align="center">{bodega.id_store}</TableCell>
                        <TableCell align="center">{bodega.code_store}</TableCell>
                        <TableCell align="center">{bodega.name_store}</TableCell>
                        <TableCell align="center">{bodega.address_store}</TableCell>
                        <TableCell align="center">
                          {bodega.location_store ? (
                            <Button
                              variant="outlined"
                              color="primary"
                              size="small"
                              href={bodega.location_store}
                              target="_blank"
                              rel="noopener noreferrer"
                            >
                              Ver mapa
                            </Button>
                          ) : (
                            <Typography variant="body2" color="text.secondary">Sin ubicación</Typography>
                          )}
                        </TableCell>
                        <TableCell align="center">{empresa ? empresa.name_company : ''}</TableCell>
                        <TableCell align="center">
                          <Button size="small" variant="outlined" sx={{ mr: 1 }} onClick={() => handleEdit(bodega)}>Editar</Button>
                          <Button size="small" variant="outlined" color="error" onClick={() => handleDelete(bodega)}>Eliminar</Button>
                        </TableCell>
                      </TableRow>
                    );
                  })
                )}
              </TableBody>
            </Table>
          </TableContainer>
          {/* Paginación */}
          <Box sx={{ display: 'flex', justifyContent: 'center', alignItems: 'center', mt: 2 }}>
            <Button
              variant="outlined"
              onClick={() => setPage((prev) => Math.max(prev - 1, 1))}
              disabled={page === 1}
              sx={{ mr: 1 }}
            >
              Anterior
            </Button>
            <Typography sx={{ mx: 2 }}>
              Página {page} de {totalPages} ({total} bodegas)
            </Typography>
            <Button
              variant="outlined"
              onClick={() => setPage((prev) => Math.min(prev + 1, totalPages))}
              disabled={page === totalPages}
              sx={{ ml: 1 }}
            >
              Siguiente
            </Button>
          </Box>
          {/* Formulario modal para crear/editar bodega */}
          <Dialog open={openForm} onClose={() => { setOpenForm(false); setEditBodega(null); }}>
            <DialogTitle>{editBodega ? 'Editar Bodega' : 'Nueva Bodega'}</DialogTitle>
            <DialogContent>
              <TextField
                margin="dense"
                label="Código"
                name="code"
                value={form.code}
                onChange={handleFormChange}
                fullWidth
                required
              />
              <TextField
                margin="dense"
                label="Nombre"
                name="name"
                value={form.name}
                onChange={handleFormChange}
                fullWidth
                required
              />
              <TextField
                margin="dense"
                label="Dirección"
                name="address"
                value={form.address}
                onChange={handleFormChange}
                fullWidth
              />
              <TextField
                margin="dense"
                label="Ubicación (URL de Google Maps)"
                name="location"
                value={form.location}
                onChange={handleFormChange}
                fullWidth
                helperText="Puedes pegar una URL o seleccionar en el mapa."
              />
              {isLoaded && (
                <GoogleMap
                  mapContainerStyle={MAP_CONTAINER_STYLE}
                  center={marker || DEFAULT_CENTER}
                  zoom={marker ? 16 : 13}
                  onClick={handleMapClick}
                >
                  {marker && <Marker position={marker} />}
                </GoogleMap>
              )}
              <Autocomplete
                options={companies}
                getOptionLabel={(option) => option.name_company || ''}
                value={companies.find(c => c.id_company === form.company_id) || null}
                onChange={handleCompanyChange}
                renderInput={(params) => (
                  <TextField {...params} label="Empresa" margin="dense" required fullWidth />
                )}
                isOptionEqualToValue={(option, value) => option.id_company === value.id_company}
              />
            </DialogContent>
            <DialogActions>
              <Button onClick={() => { setOpenForm(false); setEditBodega(null); }}>Cancelar</Button>
              <Button onClick={handleCreateOrUpdateBodega} variant="contained">{editBodega ? 'Actualizar' : 'Crear'}</Button>
            </DialogActions>
          </Dialog>
        </Container>
      </Box>
    </Box>
  );
};

export default BodegaList;
