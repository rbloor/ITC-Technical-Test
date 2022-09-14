<DOCTYPE! html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>ITC Technical Test</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <section class="mx-auto max-w-7xl sm:p-6 lg:p-8">
    <header class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Products</h2>
    </header>
    <div class="overflow-scroll shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr class="divide-x divide-gray-200">
                    <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Name</th>
                    <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Description</th>
                    <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                    <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">Suppliers</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php foreach ($productDetails as $product): ?>
                    <tr class="divide-x divide-gray-200">
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"><?= $product['name'] ?></td>
                        <td class="whitespace-wrap px-3 py-4 text-sm text-gray-500"><?= $product['description'] ?></td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"><?= $product['type']; ?></td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"><?= implode(", ", $product['suppliers']); ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
  </section>
</body>
</html>