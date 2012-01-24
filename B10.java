public class B10 {

	public static void main(String[] args) {
		// populate the array
		int testArray[] = { 19, 18, 17, 16, 15 };
		// set n
		int n = 5;
		System.out.println("n is :" + n);
		// set q
		int q = 17;
		System.out.println("q is :" + q);

		// print out the position in the array
		System.out.print("The location of the value" + n + " is: "
				+ linearSearch(testArray, n, q));

	}

	// method for the algorithm
	public static int linearSearch(int[] A, int n, int q) {
		int index = 0;
		while (index < n && A[index] != q) {
			// print out the index
			System.out.println("index is :" + index);
			index = index + 1;
			// print out the index
			System.out.println("index is :" + index);
		}
		if (index == n) {
			return -1;
		} else {
			return index;
		}
	}
}
