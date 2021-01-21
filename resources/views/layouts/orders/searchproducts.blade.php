<div class="col-12 col-md-7 col-lg-8">
    <div class="container-fluid">
        <div class="row">
            <div class="block col-12 py-2 mb-1">
                <h2 class="text-center text-md-left">@lang('Rechercher des produits')</h2>
                <div class="input-group col-12">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text" v-model="searchWord" class="form-control" placeholder="@lang('Chercher un produit')" aria-label="Product" aria-describedby="basic-addon1">
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="container-fluid">
            <div class="row mb-3 mb-md-0">

                <div v-for="(product, index) in productsFiltered" class="col-12 product mt-3 p-1">
                    <div class="row">
                        <div class="col-12 block py-2 mb-1">
                            <div class="row">
                                <div class="col-12 col-xl-12 text-center text-md-left">
                                    <span class="font-size-h4 p-0 m-0" v-text="product.code"></span>
                                    -
                                    <span class="font-size-h4 p-0 m-0" v-text="product.name"></span>
                                </div>
                                <div class="col-12 col-xl-12">
                                    <div class="d-flex justify-content-center d-xl-block">
                                        <a class="btn btn-success text-white" @click="minusItem(index, product)">
                                            <i class="fas fa-minus"></i>
                                        </a>
                                        <input type="text" v-model="product.amount" v-on:change="saveAmount(index, product, $event)" />
                                        <a class="btn btn-success text-white" @click="plusItem(index, product)">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>