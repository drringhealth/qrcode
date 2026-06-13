<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Product;
use App\Services\AuditService;

class ProductController extends Controller
{
    protected $productModel;
    protected $auditService;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->auditService = new AuditService();
    }

    public function index()
    {
        if (!$this->isAdminAuthenticated()) {
            return redirect()->to('/admin/login');
        }

        $page = $this->request->getVar('page') ?? 1;
        $perPage = 20;

        $data = [
            'title' => 'Products',
            'products' => $this->productModel->paginate($perPage, 'products'),
            'pager' => $this->productModel->pager,
        ];

        return view('admin/products/index', $data);
    }

    public function create()
    {
        if (!$this->isAdminAuthenticated()) {
            return redirect()->to('/admin/login');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|string|max_length[255]',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'numeric',
            ];

            if (!$this->validate($rules)) {
                return view('admin/products/create', ['errors' => $this->validator->getErrors()]);
            }

            $data = $this->request->getPost();
            $data['slug'] = $this->productModel->generateSlug($data['name']);
            $data['status'] = 'active';

            $productId = $this->productModel->insert($data);

            $adminId = session()->get('adminId');
            $this->auditService->log(
                $adminId,
                'products',
                'create',
                $productId,
                null,
                $data,
                'Product created'
            );

            return redirect()->to('/admin/products')->with('success', 'Product created successfully');
        }

        return view('admin/products/create');
    }

    public function edit($id = null)
    {
        if (!$this->isAdminAuthenticated()) {
            return redirect()->to('/admin/login');
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Product not found');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|string|max_length[255]',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'numeric',
            ];

            if (!$this->validate($rules)) {
                return view('admin/products/edit', ['product' => $product, 'errors' => $this->validator->getErrors()]);
            }

            $data = $this->request->getPost();

            $this->productModel->update($id, $data);

            $adminId = session()->get('adminId');
            $this->auditService->log(
                $adminId,
                'products',
                'update',
                $id,
                $product,
                $data,
                'Product updated'
            );

            return redirect()->to('/admin/products')->with('success', 'Product updated successfully');
        }

        return view('admin/products/edit', ['product' => $product]);
    }

    public function delete($id = null)
    {
        if (!$this->isAdminAuthenticated()) {
            return redirect()->to('/admin/login');
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Product not found');
        }

        $this->productModel->delete($id);

        $adminId = session()->get('adminId');
        $this->auditService->log(
            $adminId,
            'products',
            'delete',
            $id,
            $product,
            null,
            'Product deleted'
        );

        return redirect()->to('/admin/products')->with('success', 'Product deleted successfully');
    }

    private function isAdminAuthenticated()
    {
        $session = session();
        return $session->get('adminId') !== null;
    }
}
