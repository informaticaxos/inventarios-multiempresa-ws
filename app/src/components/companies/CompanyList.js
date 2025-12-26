import React, { useState, useEffect } from 'react';
import { Box, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper, Button, Typography, Toolbar, Dialog, DialogTitle, DialogContent, DialogActions, TextField, InputAdornment } from '@mui/material';
import api from '../../services/api';
import Header from '../common/Header';
import Sidebar from '../common/Sidebar';
import Swal from 'sweetalert2';

const CompanyList = () => {
  const [companies, setCompanies] = useState([]);
  const [error, setError] = useState('');
  const [page, setPage] = useState(1);
  const [limit] = useState(10);
  const [totalPages, setTotalPages] = useState(1);
  const [total, setTotal] = useState(0);
  const [openForm, setOpenForm] = useState(false);
  const [editCompany, setEditCompany] = useState(null);
  const [form, setForm] = useState({
    dni_company: '',
    name_company: '',
    phone_company: '',
    email_company: '',
    address_company: ''
  });
  const [search, setSearch] = useState('');
  const [filter, setFilter] = useState('');
  const handleEdit = (company) => {
    setEditCompany(company);
    setForm({
      dni_company: company.dni_company,
      name_company: company.name_company,
      phone_company: company.phone_company,
      email_company: company.email_company,
      address_company: company.address_company
    });
    setOpenForm(true);
  };

  const handleDelete = async (company) => {
    const result = await Swal.fire({
      title: '¿Eliminar empresa?',
      text: `¿Seguro que deseas eliminar a ${company.name_company}?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    });
    if (result.isConfirmed) {
      try {
        const response = await api.delete(`/companies/${company.id_company}`);
        if (response.data.state) {
          Swal.fire('Eliminado', 'Empresa eliminada correctamente', 'success');
          fetchCompanies(page);
        } else {
          Swal.fire('Error', response.data.message, 'error');
        }
      } catch (err) {
        Swal.fire('Error', 'No se pudo eliminar la empresa', 'error');
      }
    }
  };

  useEffect(() => {
    fetchCompanies(page);
  }, [page]);

  // Filtrado en frontend (puedes migrar a backend si lo deseas)
  const filteredCompanies = companies.filter((company) => {
    const searchLower = search.toLowerCase();
    return (
      (!search ||
        company.name_company.toLowerCase().includes(searchLower) ||
        company.dni_company.toLowerCase().includes(searchLower)) &&
      (!filter || company.dni_company === filter)
    );
  });

  const fetchCompanies = async (pageNum = 1) => {
    try {
      // Usar el endpoint y estructura de respuesta exacta proporcionada
      const response = await api.get('/companies');
      if (response.data && response.data.state === 1 && Array.isArray(response.data.data)) {
        setCompanies(response.data.data);
        setTotalPages(response.data.pagination.pages);
        setTotal(response.data.pagination.total);
        setError('');
      } else {
        setCompanies([]);
        setTotalPages(1);
        setTotal(0);
        setError(response.data?.message || 'No se encontraron empresas.');
      }
    } catch (err) {
      setCompanies([]);
      setTotalPages(1);
      setTotal(0);
      setError('Error al obtener empresas');
    }
  };

  const handleFormChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleCreateOrUpdateCompany = async () => {
    // Validación frontend
    if (!form.dni_company.trim() || !form.name_company.trim()) {
      Swal.fire({
        icon: 'warning',
        title: 'Campos requeridos',
        text: 'El campo DNI y Nombre son obligatorios.',
      });
      return;
    }
    try {
      let response;
      if (editCompany) {
        response = await api.put(`/companies/${editCompany.id_company}`, form);
      } else {
        response = await api.post('/companies', form);
      }
      if (response.data && response.data.state) {
        setOpenForm(false);
        setEditCompany(null);
        setForm({ dni_company: '', name_company: '', phone_company: '', email_company: '', address_company: '' });
        // Limpiar filtros y mostrar la primera página
        setSearch('');
        setFilter('');
        setPage(1);
        fetchCompanies(1);
        Swal.fire({
          icon: 'success',
          title: 'Éxito',
          text: editCompany ? 'Empresa actualizada correctamente' : 'Empresa creada correctamente',
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: (response.data && response.data.message) ? response.data.message : 'No se pudo guardar la empresa. Verifica los datos.',
        });
      }
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: err.response?.data?.message || err.message || (editCompany ? 'No se pudo actualizar la empresa' : 'No se pudo crear la empresa'),
      });
    }
  };

  return (
    <Box sx={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
      <Header />
      <Box sx={{ display: 'flex', flexGrow: 1 }}>
        <Box component="main" sx={{ flexGrow: 1, p: 3, overflow: 'auto' }}>
          <Toolbar />
          <Box sx={{ width: '100%', px: 0 }}>
            <Typography variant="h4" gutterBottom>
              Empresas
            </Typography>
            <Box sx={{ display: 'flex', alignItems: 'center', mb: 2, gap: 2 }}>
              <Button variant="contained" color="primary" onClick={() => setOpenForm(true)}>
                Crear Empresa
              </Button>
              <TextField
                label="Buscar por nombre o DNI"
                variant="outlined"
                size="small"
                value={search}
                onChange={e => setSearch(e.target.value)}
                sx={{ minWidth: 250 }}
                InputProps={{
                  startAdornment: <InputAdornment position="start">🔍</InputAdornment>,
                }}
              />
              <TextField
                label="Filtrar por DNI exacto"
                variant="outlined"
                size="small"
                value={filter}
                onChange={e => setFilter(e.target.value)}
                sx={{ minWidth: 180 }}
              />
            </Box>
            {error && <Typography color="error">{error}</Typography>}
            <TableContainer component={Paper} sx={{ width: '100%', boxShadow: 3, borderRadius: 2, mb: 2, ml: 0 }}>
              <Table size="medium" sx={{ minWidth: 700 }}>
                <TableHead>
                  <TableRow>
                    <TableCell align="center" sx={{ fontWeight: 'bold', width: 80 }}>ID</TableCell>
                    <TableCell align="center" sx={{ fontWeight: 'bold', width: 160 }}>DNI</TableCell>
                    <TableCell align="center" sx={{ fontWeight: 'bold', width: 180 }}>Nombre</TableCell>
                    <TableCell align="center" sx={{ fontWeight: 'bold', width: 140 }}>Teléfono</TableCell>
                    <TableCell align="center" sx={{ fontWeight: 'bold', width: 200 }}>Email</TableCell>
                    <TableCell align="center" sx={{ fontWeight: 'bold', width: 220 }}>Dirección</TableCell>
                    <TableCell align="center" sx={{ fontWeight: 'bold', width: 180 }}>Acciones</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {filteredCompanies.length === 0 ? (
                    <TableRow>
                      <TableCell colSpan={7} align="center">No hay empresas para mostrar.</TableCell>
                    </TableRow>
                  ) : (
                    filteredCompanies.map((company) => (
                      <TableRow key={company.id_company} hover>
                        <TableCell align="center">{company.id_company}</TableCell>
                        <TableCell align="center">{company.dni_company}</TableCell>
                        <TableCell align="center">{company.name_company}</TableCell>
                        <TableCell align="center">{company.phone_company}</TableCell>
                        <TableCell align="center">{company.email_company}</TableCell>
                        <TableCell align="center">{company.address_company}</TableCell>
                        <TableCell align="center">
                          <Button size="small" variant="outlined" sx={{ mr: 1 }} onClick={() => handleEdit(company)}>Editar</Button>
                          <Button size="small" variant="outlined" color="error" onClick={() => handleDelete(company)}>Eliminar</Button>
                        </TableCell>
                      </TableRow>
                    ))
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
                Página {page} de {totalPages} ({total} empresas)
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
            {/* Formulario modal para crear empresa */}
            <Dialog open={openForm} onClose={() => { setOpenForm(false); setEditCompany(null); }}>
              <DialogTitle>{editCompany ? 'Editar Empresa' : 'Nueva Empresa'}</DialogTitle>
              <DialogContent>
                <TextField
                  margin="dense"
                  label="DNI"
                  name="dni_company"
                  value={form.dni_company}
                  onChange={handleFormChange}
                  fullWidth
                  required
                />
                <TextField
                  margin="dense"
                  label="Nombre"
                  name="name_company"
                  value={form.name_company}
                  onChange={handleFormChange}
                  fullWidth
                  required
                />
                <TextField
                  margin="dense"
                  label="Teléfono"
                  name="phone_company"
                  value={form.phone_company}
                  onChange={handleFormChange}
                  fullWidth
                />
                <TextField
                  margin="dense"
                  label="Email"
                  name="email_company"
                  value={form.email_company}
                  onChange={handleFormChange}
                  fullWidth
                />
                <TextField
                  margin="dense"
                  label="Dirección"
                  name="address_company"
                  value={form.address_company}
                  onChange={handleFormChange}
                  fullWidth
                />
              </DialogContent>
              <DialogActions>
                <Button onClick={() => { setOpenForm(false); setEditCompany(null); }}>Cancelar</Button>
                <Button onClick={handleCreateOrUpdateCompany} variant="contained">{editCompany ? 'Actualizar' : 'Crear'}</Button>
              </DialogActions>
            </Dialog>
          </Box>
        </Box>
      </Box>
    </Box>
  );
};

export default CompanyList;