<div>
    <div v-for="(product, index) in listAddedProducts" class="col-12 product-item mb-1 p-0">
        <div class="row mb-1">
            <div class="col-12 col-xl-12 block text-center text-md-left mb-1">
                <span class="p-0 m-0" v-text="getCodeProduct(index, product)"></span>
                -
                <span class="p-0 m-0" v-text="getNameProduct(index, product)"></span>
            </div>
            <div class="col-12 col-xl-12">
                <div class="d-flex justify-content-center d-xl-block">
                    <input type="text" :name="'products['+ getIdProduct(index, product) +']'" v-model="product.amount" v-on:change="saveAmountAdded(index, product, $event)" />
                    <a class="btn btn-danger text-white" @click="deleteItem(index, product)">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>