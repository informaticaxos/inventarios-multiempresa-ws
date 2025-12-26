
import React, { useState, useEffect } from 'react';
import Swal from 'sweetalert2';
import {
  Box, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper, Button, Typography, Toolbar, Container, Dialog, DialogTitle, DialogContent, DialogActions, TextField, InputAdornment, Autocomplete
} from '@mui/material';
import api from '../../services/api';
import Header from '../common/Header';

const ProductList = () => {
  const [products, setProducts] = useState([]);
  const [companies, setCompanies] = useState([]);
  const [bodegas, setBodegas] = useState([]);
  const [error, setError] = useState('');
  const [search, setSearch] = useState('');
  const [empresaFiltro, setEmpresaFiltro] = useState(null);
  const [bodegaFiltro, setBodegaFiltro] = useState(null);
  const [page, setPage] = useState(1);
  const [limit] = useState(10);
  const [totalPages, setTotalPages] = useState(1);
  const [total, setTotal] = useState(0);
  const [openForm, setOpenForm] = useState(false);
  const [editProduct, setEditProduct] = useState(null);
  const [form, setForm] = useState({
    code: '',
    name: '',
    description: '',
    pvp: '',
    min: '',
    max: '',
    state: 1,
  });
  // ...rest of the ProductList component implementation goes here...
};

export default ProductList;